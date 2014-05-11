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
    }
?>