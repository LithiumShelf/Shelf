<?php
    //Still missing a page with a form to fill out
    //details about items you are about to put up for lending
    
    class itemsController extends controller{
        function putupforlending(){
            $params = array(':userid' => $_SESSION['userid'],
                            ':asin' => $_POST['asin'],
                            ':category' => $_POST['category'],
                            ':picURL' => $_POST['picurl'],
                            ':status' => "Available");
            $this->set('inventory', $this->Item->query('INSERT INTO :thistable VALUES (null, :asin, :category, :picURL, :status, :userid)'));
        }
        
        function removefromlending($id){
            $params = array(':id' => $id);
            $this->set('inventory', $this->Item->query('DELETE FROM :thistable WHERE id = :id');
        }
        
        function itempage(){
            $this->set('item', $this->Item->select($id));
        }
        
        function itemsbyuser($userid){
            $params = array(':userid' => $userid);
            $this->set('inventory', $this->Item->query('SELECT Item.* FROM :thistable JOIN Account ON (Item.LenderID = Account.id) WHERE Account.id = :userid'));
        }
    }
?>