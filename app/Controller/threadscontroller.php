<?php
    class threadsController extends Controller{
        function incrementrep(){
            //action must be "Lent" or "Borrowed"
            // DO NOT USE incrementrep, PLEASE INTEGRATE THIS FUNCTIONALITY INTO
            // CHANGE STATUS TO MONITOR FOR CERTAIN SET PHASES IN ITEM STATUS
            //$params = array(':action' => $action,
             //               ':UserID' => $_SESSION['userid']);
            //$this->set('reputation', $this->Thread->query('UPDATE Account SET :action = :action + 1 WHERE id = :UserID', $params));
        }
        
        function itemrequest(){
            $hashCode = hash('CRC32', $_POST['itemid'] . $_SESSION['userid'] );
            $params = array(':itemid' => $_POST['itemid'],
                            ':BorrowerID' => $_SESSION['userid'],
                            ':DueDate' => null, //$_POST['DueDate'],
                            ':threadstatus' => "Requested",
                            ':hashCode' => $hashCode
                            );
            $this->set('thread', $this->Thread->query('INSERT INTO Thread (ThreadStatus, BorrowerID, ItemID, DueDate, HashCode)
                                                      VALUES (:threadstatus, :BorrowerID, :itemid, :DueDate, :hashCode)', $params));
        }

        function itemoffer(){
            $params = array(':itemid' => $_POST['itemid'],
                            ':BorrowerID' => $_POST['userid'],
                            ':DueDate' => null, //$_POST['DueDate'],
                            ':threadstatus' => "Offered",
                            ':hashCode' => $hashCode
                            );
            $this->set('thread', $this->Thread->query('INSERT INTO Thread (ThreadStatus, BorrowerID, ItemID, DueDate)
                                                      VALUES (:threadstatus, :BorrowerID, :itemid, :DueDate)', $params));
        }
		
		/*
			-Award points
			-
		*/
        function changestatus(){
            /*
             *@Params
             *$_SESSION[":userid"]
             *$_POST['id'] (Thread id)
             *$_POST['action']
             *$_POST["$page"]
             */
            $availability = null;
            $page = $_POST["page"];
            $threadid = $_POST['id'];
            $params = array(':userid' => $_SESSION["userid"],
                            ':threadid' => $threadid);
            if($page == "borrow"){
               $thread = $this->Thread->isBorrower($params);
            } elseif ($page == "lend"){
               $thread = $this->Thread->isLender($params);
            }else{
                die("Error");
            }
            //confirmed role in, page setting, and participation in thread
            if(isset($thread)){
                switch($page){
                    case "borrow":
                        switch($thread["ThreadStatus"]){
                            case "offered": //borrower -> requested
                                $changeStatusTo = "requested";
                                break;
                            case "approved": //borrower -> current
                            //force borrower to enter hashcode
                            //set availability to unavailable
                                if(isset($_POST['hashCode']) && $_POST['hashCode'] == $thread["hashCode"]){
                                    if(isset($_POST['action']) && $_POST['action'] == "current"){
                                        $changeStatusTo = "current";
                                        $availability = "unavailable";
                                    }
                                    $changeStatusTo = "cancelled";
                                    $this->Thread->givePoint($thread["BorrowerID"], "borrow");
                                    $this->Thread->givePoint($thread["LenderID"], "lend");
                                    $this->Thread->givePoint($thread["LenderID"], "success");
                                }else{
                                    die("incorrect code");
                                }
                                break;
                             default: //fail and die
                                die("One or more parameters are out of bounds, please contact Shelf development team");
                        }
                        break;
                    case "lend":
                        switch($thread["ThreadStatus"]){
                            case "requested": //lender -> approve or reject
                                //display hashcode to lender (handled in view)
                                $changeStatusTo = $_POST['action']; //"approved" or "rejected"
                                break;
                            case "current": //lender -> "rejected", "complete", "late", "failed"
                                //increment lend for lender and borrow for borrower
                                /*
                                    Set to either "complete", "late", "failed"
                                */
                                $changeStatusTo = $_POST['action']; //complete or failed
                                if($changeStatusTo == "complete"){
                                    if(isset($thread["DueDate"]) && date("Y-m-d H:i:s") > $thread["DueDate"]){
                                        $changeStatusTo = "late";
                                    }
                                    $this->Thread->givePoint($thread["BorrowerID"], "borrow");
                                    $availability = "available";
                                }else{
                                    $changeStatusTo = "failed";
                                    $availability = "missing";
                                }
                                break;
                            default: //fail and die
                                die("One or more parameters are out of bounds, please contact Shelf development team");
                        }
                        break;
                    default://fail and die
                        die("The page you are in is out of bounds, please contact Shelf development team");
                }
                //echo $changeStatusTo;
                $params = array(':threadid' => $threadid,
                                ':newstatus' => $changeStatusTo);
                //change thread status
                $this->set('thread', $this->Thread->query('UPDATE Thread SET ThreadStatus=:newstatus WHERE id=:threadid', $params));
                if(isset($availability)){
                    $params = array(':itemid' => $thread["ItemID"],
                        ':availability' => $availability);
                    $this->set('itemlock', $this->Thread->query('UPDATE Item SET ItemStatus=:availability WHERE id = :itemid', $params));
                }
                echo "success";
                //change item availability
            }else{
                echo "There was an error";
            }

        }
        
        function viewallthreads(){
		global $page;
            $params = array(':UserID' => 2 /*$_SESSION['userid']*/);
			
			//SQL code that orders threads by stats (custom order). To be appended to end of lend/borrow queries.
			$orderby = " ORDER BY ( CASE WHEN ThreadStatus = 'requested' OR ThreadStatus = 'approved' OR ThreadStatus = 'waiting' THEN 0	WHEN ThreadStatus = 'current' OR ThreadStatus='Open' THEN 1 ELSE 3	END),ThreadStatus, DueDate DESC";
			
            if($page != "feed"){
                //Select all your threads
                GLOBAL $page;
                //$_SESSION['userid'] = 2;
                
                if($page == "lend"){
                    //Find threads for which you are the lender
                    //$params[":actionID"] = "LenderID";
					$this->set('type', "lend");
					$this->set('threads', $this->Thread->query("SELECT *, Thread.id AS ThreadID FROM Thread RIGHT JOIN Item ON (Item.id = Thread.ItemID) JOIN Account ON (Thread.BorrowerID = Account.id) WHERE LenderID = :UserID".$orderby, $params));
				}elseif($page == "borrow"){
                    //Find threads for which you are the borrower
                    //$params[":actionID"] = "BorrowerID";
					$this->set('type', "borrow");
					
                //$this->set('threads', $this->Thread->query('SELECT * FROM Thread RIGHT JOIN Item ON (Item.id = Thread.ItemID) JOIN Account ON (Thread.BorrowerID = Account.id) WHERE BorrowerID = :UserID', $params));
				$this->set('threads', $this->Thread->query('SELECT *, Thread.id AS ThreadID FROM Thread RIGHT JOIN Item ON (Item.id = Thread.ItemID) JOIN Account ON (Item.LenderID = Account.id) WHERE BorrowerID = :UserID'.$orderby, $params));
                //GROUP BY Thread Status
				}

            }else{
				$this->set('type', "feed");
                //Select the top 10 public (your friends) threads
                //JOIN with your friends
                //Reverse chronological order (check the timestamp);
				 $this->set('threads', $this->Thread->query('SELECT DISTINCT Thread.*, Item.*, A1.firstName as bFirst, A1.lastName as bLast, A1.profilePic, A2.firstName as lFirst, A2.lastName as lLast FROM Thread JOIN Item ON (Item.id = Thread.ItemID) JOIN Account A1 ON (BorrowerID = A1.id) JOIN Account A2 ON (Item.LenderID = A2.id)
  JOIN Friend F1 ON (A1.id = F1.User) JOIN Friend F2 ON (A2.id = F2.User) WHERE (F1.Friend = :UserID OR F2.Friend = :UserID) AND (F1.User != :UserID  AND F2.User != :UserID) LIMIT 10', $params));   
				/*
                $this->set('threads', $this->Thread->query('SELECT Thread.*, Item.* FROM Thread JOIN Item ON (Item.id = Thread.ItemID) JOIN Account A1 ON (BorrowerID = A1.id) JOIN Account A2 ON (Item.LenderID = A2.id)
  JOIN Friend F1 ON (A1.id = F1.User) JOIN Friend F2 ON (A2.id = F2.User) WHERE (F1.Friend = :UserID OR F2.Friend = :UserID) AND (F1.User != :UserID  AND F2.User != :UserID) LIMIT 10', $params));  
				*/
            }
        }
        
        function viewthread(){
            $params = array(':id' => $_POST["id"]);
            $this->set('messages', $this->Thread->query('SELECT Lender.Username AS LenderName, Borrower.Username AS BorrowerName, Message.*
                FROM Thread JOIN Item ON (Thread.ItemID = Item.id) 
                JOIN Account Borrower ON (Borrower.id = Thread.BorrowerID) JOIN Account Lender ON (Lender.id = Item.LenderID)
                JOIN Message ON (Message.ThreadID = Thread.id) WHERE ThreadID = :id ORDER BY Messagetimestamp DESC', $params));
            // ADD CODE TO UPDATE THE ABOVE TABLE AND MARK ALL hasRead as "true"
            $this->Thread->query('UPDATE Message SET hasRead = 1 WHERE ThreadID = :id', $params);
            //In view, If fromBorrower = 1, print Borrower Name, If 0, print your User's name
        }
        
        function composemessage(){
            $page = $_POST['page'];
            $threadid = $_POST['threadID'];
            //add a new message to a thread
            $params = array(':userid' => $_SESSION["userid"],
                ':threadid' => $threadid);
            if($page == "borrow"){
               $thread = $this->Thread->isBorrower($params);
            } elseif ($page == "lend"){
               $thread = $this->Thread->isLender($params);
            }else{
                die("Error");
            }
            if(isset($thread)){
                switch($page){
                    case "borrow":
                        $isBorrower = 1;
                        break;
                    case "lend":
                        $isBorrower = 0;
                        break;
                    default:
                        die("Parameter out of bounds");
                        break;
		}
            }
            $params = array(':subject' => $_POST['subject'],
                            ':body' => $_POST['body'],
                            ':thread' => $threadid,
                            ':isBorrower' => $isBorrower);
            $lastInsertMsgID = $this->Thread->query('INSERT INTO Message VALUES (NULL, NOW(), :subject, :body, 0, :thread, :isBorrower)', $params);
            $params = array(':messageid' => $lastInsertMsgID);
            //capture the previously inserted message
            $this->set('message', $this->Thread->query('SELECT * FROM messages WHERE id = :messageid', $params));
            //In Ajax Call, If fromBorrower = 1, print Borrower Name, If 0, print your User's name
        }
        
        function readmessage(){
            //This might not need to be programmed, because the "viewthread" JOINS Thread to Message anyways.
        }
    }

?>