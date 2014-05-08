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
            $params = array(':itemid' => $_POST['itemid'],
                            ':BorrowerID' => $_SESSION['userid'],
                            ':DueDate' => $_POST['DueDate'],
                            ':threadstatus' => "Init"
                            );
            $this->set('thread', $this->Thread->query('INSERT INTO Thread (ThreadStatus, BorrowerID, ItemID, DueDate)
                                                      VALUES (:threadstatus, :BorrowerID, :itemid, :DueDate)', $params));
        }
        
		
		/*
			-Award points
			-
		*/
        function changestatus(){
            //change thread status
            $params = array(':id' => $_POST['id'],
                            ':nextstatus' => $_POST('nextstatus'),
                            ':availability' => $_POST('availability'));
            $this->set('thread', $this->Thread->query('UPDATE Thread SET ThreadStatus=:newstatus WHERE id=:id'));
            if($availability){
                $this->set('itemlock', $this->Thread->query('UPDATE Item SET ItemStatus=:availability WHERE (SELECT ItemID FROM Thread WHERE id=:id)', $params));
            }
        }
        
        function viewallthreads(){
			global $page;
            if($page != "feed"){
                //Select all your threads
                GLOBAL $page;
                //$_SESSION['userid'] = 2;
                $params = array(':UserID' => 2 /*$_SESSION['userid']*/);
                if($page == "lend"){
                    //Find threads for which you are the lender
                    //$params[":actionID"] = "LenderID";
					$this->set('type', "lend");
					$this->set('threads', $this->Thread->query('SELECT * FROM Thread RIGHT JOIN Item ON (Item.id = Thread.ItemID) JOIN Account ON (Thread.LenderID = Account.id) WHERE LenderID = :UserID', $params));
				}elseif($page == "borrow"){
                    //Find threads for which you are the borrower
                    //$params[":actionID"] = "BorrowerID";
					$this->set('type', "borrow");
                
				/*
					SELECT * FROM Thread RIGHT JOIN Item ON (Item.id = Thread.ItemID) 
					JOIN Account as Borrow ON (Thread.BorrowerID = brrw.id)
					JOIN Account as Lend ON (Item.LenderID = lnd.id)
				*/
				$this->set('threads', $this->Thread->query('SELECT * FROM Thread RIGHT JOIN Item ON (Item.id = Thread.ItemID) JOIN Account ON (Thread.BorrowerID = Account.id) WHERE BorrowerID = :UserID', $params));
                //GROUP BY Thread Status
				}

            }else{
				$this->set('type', "feed");
                //Select the top 10 public (your friends) threads
                //JOIN with your friends
                //Reverse chronological order (check the timestamp);
                $this->set('threads', $this->Thread->query('SELECT * FROM Thread JOIN Item ON (Item.id = Thread.ItemID) LIMIT 10', $params));   
            }
        }
        
        function viewthread($id){
            $params = array(':id' => $id);
            $this->set('thread', $this->Thread->query('SELECT * FROM Thread JOIN Message ON (Message.ThreadID = Thread.id) WHERE id = :id', $params));
            // ADD CODE TO UPDATE THE ABOVE TABLE AND MARK ALL hasRead as "true"
        }
        
        function composemessage($threadID){
            //add a new message to a thread
            $params = array(':subject' => $_POST['subject'],
                            ':body' => $_POST['body'],
                            ':thread' => $threadID);
            $this->set('message', $this->Thread->query('INSERT INTO Message VALUES (NULL, NOW(), :subject, :body, false, :thread)', $params));
        }
        
        function readmessage(){
            //This might not need to be programmed, because the "viewthread" JOINS Thread to Message anyways.
        }
    }

?>