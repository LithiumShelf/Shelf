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
             *
             */
            global $page;
            $threadid = $_POST['id'];
            $params = array(':userid' => $_SESSION[":userid"],
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
                            case "approved": //borrower -> current
                            //force borrower to enter hashcode
                            //set availability to unavailable
                                if(isset($_POST['hashCode']) && $_POST['hashCode'] == $thread["hashCode"]){
                                    $changeStatusTo = "current";
                                    $availability = "unavailable";
                                    $this->Thread->givePoint($thread["BorrowerID"], "borrow");
                                    $this->Thread->givePoint($thread["LenderID"], "lend");
                                    $this->Thread->givePoint($thread["LenderID"], "success");
                                }else{
                                    die("incorrect code");
                                }
                             default: //fail and die
                                die("One or more parameters are out of bounds, please contact Shelf development team");
                        }
                        
                    case "lend":
                        switch($thread["ThreadStatus"]){
                            case "requested": //lender -> approve or reject
                                //display hashcode to lender (handled in view)
                                $changeStatusTo = $_POST['action']; //"approved" or "rejected"
                            case "current": //lender -> "rejected", "complete", "late", "failed"
                                //increment lend for lender and borrow for borrower
                                /*
                                    Set to either "complete", "late", "failed"
                                */
                                $changeStatusTo = "$_POST['action']"; //complete or failed
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
                            default: //fail and die
                                die("One or more parameters are out of bounds, please contact Shelf development team");
                        }
                    default://fail and die
                    die("One or more parameters are out of bounds, please contact Shelf development team");
                }
                
                //change thread status
                $this->set('thread', $this->Thread->query('UPDATE Thread SET ThreadStatus=:newstatus WHERE id=:threadid'));
                if($availability){
                    $params = array(':itemid' => $thread["ItemID"],
                        ':nextstatus' => $changeStatusTo,
                        ':availability' => $availability);
                    $this->set('itemlock', $this->Thread->query('UPDATE Item SET ItemStatus=:availability WHERE id = :itemid', $params));
                }
                //change item availability
            }else{
                echo "There was an error";
            }

        }
        
        function viewallthreads(){
		global $page;
            $params = array(':UserID' => 2 /*$_SESSION['userid']*/);
            if($page != "feed"){
                //Select all your threads
                GLOBAL $page;
                //$_SESSION['userid'] = 2;
                
                if($page == "lend"){
                    //Find threads for which you are the lender
                    //$params[":actionID"] = "LenderID";
					$this->set('type', "lend");
					$this->set('threads', $this->Thread->query('SELECT * FROM Thread RIGHT JOIN Item ON (Item.id = Thread.ItemID) JOIN Account ON (Item.LenderID = Account.id) WHERE LenderID = :UserID', $params));
				}elseif($page == "borrow"){
                    //Find threads for which you are the borrower
                    //$params[":actionID"] = "BorrowerID";
					$this->set('type', "borrow");
                
				$this->set('threads', $this->Thread->query('SELECT * FROM Thread RIGHT JOIN Item ON (Item.id = Thread.ItemID) JOIN Account ON (Thread.BorrowerID = Account.id) WHERE BorrowerID = :UserID', $params));
                //GROUP BY Thread Status
				}

            }else{
				$this->set('type', "feed");
                //Select the top 10 public (your friends) threads
                //JOIN with your friends
                //Reverse chronological order (check the timestamp);
                $this->set('threads', $this->Thread->query('SELECT Thread.*, Item.* FROM Thread JOIN Item ON (Item.id = Thread.ItemID) JOIN Account A1 ON (BorrowerID = A1.id) JOIN Account A2 ON (Item.LenderID = A2.id)
  JOIN Friend F1 ON (A1.id = F1.User) JOIN Friend F2 ON (A2.id = F2.User) WHERE (F1.Friend = 2 OR F2.Friend = 2) AND (F1.User != 2  AND F2.User != 2) LIMIT 10', $params));   
            }
        }
        
        function viewthread($id){
            $params = array(':id' => $id);
            $this->set('messages', $this->Thread->query('SELECT Lender.Username AS LenderName, Borrower.Username AS BorrowerName, Message.*
                FROM Thread JOIN Item ON (Thread.ItemID = Item.id) 
                JOIN Account Borrower ON (Borrower.id = Thread.BorrowerID) JOIN Account Lender ON (Lender.id = Item.LenderID)
                JOIN Message ON (Message.ThreadID = Thread.id) WHERE Thread.id = :id ORDER BY Messagetimestamp', $params));
            // ADD CODE TO UPDATE THE ABOVE TABLE AND MARK ALL hasRead as "true"
            $this->Thread->query('UPDATE Message SET hasRead = 1 WHERE ThreadID = :id', $params);
            //In view, If fromBorrower = 1, print Borrower Name, If 0, print your User's name
        }
        
        function composemessage(){
            global $page;
            //add a new message to a thread
            $params = array(':userid' => $_SESSION[":userid"],
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
                    case "lend":
                        $isBorrower = 0;
                    default:
                        die("Parameter out of bounds");
            }
            $params = array(':subject' => $_POST['subject'],
                            ':body' => $_POST['body'],
                            ':thread' => $_POST['threadID'],
                            ':isBorrower' => $isBorrower);
            $this->set('message', $this->Thread->query('INSERT INTO Message VALUES (NULL, NOW(), :subject, :body, 0, :thread, :isBorrower)', $params));
        }
        
        function readmessage(){
            //This might not need to be programmed, because the "viewthread" JOINS Thread to Message anyways.
        }
    }

?>