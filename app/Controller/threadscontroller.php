<?php
    class transactionsController extends controller{
        function incrementrep($action){
            //action must be "Lent" or "Borrowed"
            $params = array(':action' => $action
                            ':UserID' => $_SESSION['userid']);
            $this->set('reputation', $this->Thread->query('UPDATE Reputation SET :action = :action + 1 WHERE id = :UserID'););
        }
        
        function itemrequest($itemid){
            $params = array(':itemid' => $itemid,
                            ':BorrowerID' => $_SESSION['userid'],
                            ':DueDate' => $_POST['DueDate'],
                            ':threadstatus' => "Init"
                            );
            $this->set('thread', $this->Thread->query('INSERT INTO :thistable (ThreadStatus, BorrowerID, ItemID, DueDate)
                                                      VALUES (:threadstatus, :BorrowerID, :itemid, :DueDate)');
        }
        
        function changestatus($id, $nextstatus, $availability = null, $hash = null){
            //change thread status
            $params = array(':id' => $id,
                            ':nextstatus' => $nextstatus,
                            ':availability' => $availability);
            $this->set('thread', $this->Thread->query('UPDATE :thistable SET ThreadStatus=:newstatus WHERE id=:id'));
            if($availability){
                $this->set('itemlock', $this->Thread->query('UPDATE Item SET ItemStatus=:availability WHERE (SELECT ItemID FROM :thistable WHERE id=:id)'));
            }
        }
        
        function viewallthreads($public = null){
            if(!$public){
                //Select all your threads
                $params = array(':UserID' => $_SESSION['userid']);
                $this->set('threads', $this->Thread->query('SELECT * FROM :thistable WHERE BorrowerID = :accountID'));
            }else{
                //Select the top 10 public threads
                $this->set('threads', $this->Thread->query('SELECT * FROM :thistable LIMIT 10'));   
            }
        }
        
        function viewthread($id){
            $params = array(':id' => $id);
            $this->set('thread', $this->Thread->query('SELECT * FROM :thistable JOIN Message ON (Message.id = Thread.id) WHERE id = :id'));
        }
        
        function composemessage(){
            
        }
        
        function friendrequest(){
            
        }
        
        function sendmessage(){
            
        }
        
        function readmessage(){
            
        }
    }

?>