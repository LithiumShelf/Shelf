<?php
    class Item extends Model{
        //select * from $_table
            function selectAll() {
            $query = 'SELECT * FROM Item';
            return $this->query($query);
        }
        
        //select $id from $_table
        function select($id) {
            $query = 'SELECT * FROM Item WHERE id = :id';
            $params = array(':id' => $id);
            return $this->query($query, $params);   
        }
        
        function selectjoinaccount($id){
            $query = 'SELECT * FROM Item JOIN Account ON (Item.LenderID = Account.id) WHERE Item.id = :id';
            $params = array(':id' => $id);
            return $this->query($query, $params); 
        }
        
        //delete by id
        function delete($id) {
            $query = 'DELETE FROM Item WHERE id = :id';
            $params = array(':id' => $id);
            return $this->query($query, $params);  
        }
    }
?>