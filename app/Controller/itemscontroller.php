<?php
    //Still missing a page with a form to fill out
    //details about items you are about to put up for lending
    
    class itemsController extends Controller{
        
        function lendingform(){
            //pass keywords into the object as a $variable
            //check if variable is filled, if not then show a search form
            if(isset($_GET["keywords"])){
                $this->set('keywords', $_GET["keywords"]);
            }
        }
        
        function putupforlending(){
            $params = array(':userid' => $_SESSION['userid'],
                            ':asin' => $_POST['asin'],
                            ':category' => $_POST['category'],
                            ':picURL' => $_POST['picurl'],
                            ':status' => "Available");
            $this->set('inventory', $this->Item->query('INSERT INTO :thistable VALUES (null, :asin, :category, :picURL, :status, :userid)', $params));
        }
        
        function removefromlending($id){
            $params = array(':id' => $id);
            $this->set('inventory', $this->Item->query('DELETE FROM :thistable WHERE id = :id', $params));
        }
        
        function itempage(){
            $this->set('item', $this->Item->select($id));
        }
        
        function findbyuser($userid){
            $params = array(':userid' => $userid);
            $this->set('inventory', $this->Item->query('SELECT Item.* FROM :thistable JOIN Account ON (Item.LenderID = Account.id) WHERE Account.id = :userid', $params));
        }
        
        function findbycategory($category){
            $params = array(':category' => $category);
            $this->set('items', $this->Item->query('SELECT Item.* FROM Item JOIN Category ON (Category.id = Item.CategoryID) WHERE Category.Category = :category', $params));
        }
    }
?>