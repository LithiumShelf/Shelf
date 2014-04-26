<?php
    class transactionsController extends controller{
        function incrementrep($action){
            //action must be "Lent" or "Borrowed"
            $params = array(':action' => $action
                            ':UserID' => $_SESSION['userid']);
            $this->set('reputation', $this->Thread->query('UPDATE Reputation SET :action = :action + 1 WHERE id = :UserID', $params););
        }
        
        function itemrequest($itemid){
            $params = array(':itemid' => $itemid,
                            ':BorrowerID' => $_SESSION['userid'],
                            ':DueDate' => $_POST['DueDate'],
                            ':threadstatus' => "Init"
                            );
            $this->set('thread', $this->Thread->query('INSERT INTO :thistable (ThreadStatus, BorrowerID, ItemID, DueDate)
                                                      VALUES (:threadstatus, :BorrowerID, :itemid, :DueDate)', $params));
        }
        
        function changestatus($id, $nextstatus, $availability = null, $hash = null){
            //change thread status
            $params = array(':id' => $id,
                            ':nextstatus' => $nextstatus,
                            ':availability' => $availability);
            $this->set('thread', $this->Thread->query('UPDATE :thistable SET ThreadStatus=:newstatus WHERE id=:id'));
            if($availability){
                $this->set('itemlock', $this->Thread->query('UPDATE Item SET ItemStatus=:availability WHERE (SELECT ItemID FROM :thistable WHERE id=:id)', $params));
            }
        }
        
        function viewallthreads(){
            if($page != "feed"){
                //Select all your threads
                GLOBAL $page;
                $params = array(':UserID' => $_SESSION['userid'];
                if($page == "lend"){
                    //Find threads for which you are the lender
                    $params[":actionID"] = "LenderID"
                }elseif($page == "borrow"){
                    //Find threads for which you are the borrower
                    $params[":actionID"] = "BorrowerID"
                }
                $this->set('threads', $this->Thread->query('SELECT * FROM :thistable JOIN Item ON (Item.id = Thread.ItemID) WHERE :actionID = :accountID', $params));
                //GROUP BY Thread Status
            }else{
                //Select the top 10 public (your friends) threads
                //JOIN with your friends
                //Reverse chronological order (check the timestamp);
                $this->set('threads', $this->Thread->query('SELECT * FROM :thistable JOIN Item ON (Item.id = Thread.ItemID) LIMIT 10', $params));   
            }
        }
        
        function viewthread($id){
            $params = array(':id' => $id);
            $this->set('thread', $this->Thread->query('SELECT * FROM :thistable JOIN Message ON (Message.ThreadID = Thread.id) WHERE id = :id', $params));
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