<?php
    class Thread extends Model{
        //select * from $_table
            function selectAll() {
            $query = 'SELECT * FROM Thread';
            return $this->query($query);
        }
        
        //select $id from $_table
        function select($id) {
            $query = 'SELECT * FROM Thread WHERE id = :id';
            $params = array(':id' => $id);
            return $this->query($query, $params);   
        }
        
        //delete by id
        function delete($id) {
            $query = 'DELETE FROM Thread WHERE id = :id';
            $params = array(':id' => $id);
            return $this->query($query, $params);  
        }

        function joinitem($params, $queryAppend = null) {
            
        }
        
        function isBorrower($params){
            //$params = array(':userid' => $_SESSION[":userid"],
            //                ':threadid' => $threadid);
            $borrower = $this->query('SELECT Thread.id, Thread.ItemID, Thread.ThreadStatus, Thread.BorrowerID, Item.LenderID, Thread.DueDate, Thread.HashCode FROM Thread JOIN Item ON (Thread.ItemID = Item.id) WHERE Thread.id = :threadid AND Thread.BorrowerID = :userid', $params);
            if($borrower){
                return $borrower[0];
            }else{
                die("you are not the borrower");
            }
        }
        
        function isLender($params){
            //$params = array(':userid' => $_SESSION[":userid"],
            //                ':threadid' => $threadid);
            $lender = $this->query('SELECT Thread.id, Thread.ItemID, Thread.ThreadStatus, Thread.BorrowerID, Item.LenderID, Thread.DueDate, Thread.HashCode FROM Thread JOIN Item ON (Thread.ItemID = Item.id) WHERE Thread.id = :threadid AND Item.LenderID = :userid', $params);
            if($lender){
                return $lender[0];
            }else{
                die("you are not the lender");
            }
        }
        
        function givePoint($id, $role){ //$role is lend/borrow/success
            $params = array(':id' => $id);
            switch($role){
                case "lend":
                    $this->query('UPDATE Account SET Lent = Lent + 1 WHERE id = :id', $params);
                    break;
                case "borrow":
                    $this->query('UPDATE Account SET Borrowed = Borrowed + 1 WHERE id = :id', $params);
                    break;
                case "success":
                    $this->query('UPDATE Account SET numSuccessful = numSuccessful + 1 WHERE id = :id', $params);
                    break;
            }
        }
        
        function startThread($initStat, $itemID, $userid, $dueDate = null){
            $params = array(':BorrowerID' => $userid,
                            ':itemid' => $itemID);
            $inProgress = $this->query('SELECT * FROM Thread WHERE BorrowerID = :BorrowerID AND ItemID = :itemid AND (ThreadStatus = "offered" OR ThreadStatus = "requested" OR ThreadStatus = "approved" OR ThreadStatus = "current" OR ThreadStatus = "Open")', $params);
            if(!$inProgress){
                $hashCode = hash('CRC32', $itemID . $userid );
                $params = array(':itemid' => $itemID,
                                ':BorrowerID' => $userid,
                                ':DueDate' => $dueDate,
                                ':threadstatus' => $initStat,
                                ':hashCode' => $hashCode
                                );
                return $this->query('INSERT INTO Thread (ThreadStatus, BorrowerID, ItemID, DueDate, HashCode)
                                                          VALUES (:threadstatus, :BorrowerID, :itemid, :DueDate, :hashCode)', $params);
            }else{
                die("Similar Item Already in Progress");
            }
        }
    }
?>