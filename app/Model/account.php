<?php
    class Account extends Model{
        //select * from $_table
            function selectAll() {
            $query = 'SELECT * FROM Account';
            return $this->query($query);
        }
        
        //select $id from $_table
        function select($id) {
            $query = 'SELECT * FROM Account WHERE id = :id';
            $params = array(':id' => $id);
            return $this->query($query, $params);   
        }
        
        //delete by id
        function delete($id) {
            $query = 'DELETE FROM Account WHERE id = :id';
            $params = array(':id' => $id);
            return $this->query($query, $params);  
        }
        
        function potentialfriends($params){
            $query = 'SELECT DISTINCT a2.*, friends.User FROM Account a1 JOIN Account a2 ON (a1.LocationID = a2.LocationID) LEFT JOIN friends ON (a2.id = friends.Friend) WHERE a1.id = 2 AND a2.id != :userid AND friends.User != :userid OR friends.User IS null';
            return $this->query($query, $params);  
        }
    }
?>