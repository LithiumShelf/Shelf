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
    }
?>