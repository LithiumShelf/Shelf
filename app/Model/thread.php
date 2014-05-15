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
            if($lender){
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
                    return $this->Thread->query('UPDATE Account SET Lent = Lent + 1 WHERE id = :id', $params);
                case "borrow":
                    return $this->Thread->query('UPDATE Account SET Borrowed = Borrowed + 1 WHERE id = :id', $params);
                case "success":
                    return $this->Thread->query('UPDATE Account SET numSuccessful = numSuccessful + 1 WHERE id = :id', $params);
            }
        }
    }
?>