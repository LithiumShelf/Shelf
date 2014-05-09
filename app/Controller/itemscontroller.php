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
            $this->set('inventory', $this->Item->query('INSERT INTO Item VALUES (null, :asin, :category, :picURL, :status, :userid)', $params));
        }
        
        function removefromlending($id){
            $params = array(':id' => $id);
            $this->set('inventory', $this->Item->query('DELETE FROM Item WHERE id = :id', $params));
        }
        
        function itempage(){
            $this->set('item', $this->Item->select($id));
        }
        
        function findbyuser(){
            //Get Friends
            $params = array(':userid' => $_SESSION['userid']);
            $this->set('friends', $this->Account->query('SELECT * FROM loztwodc_shelf.friends WHERE User = :userid;)', $params));
            //Get Local
            //find hot item owners in your area
            $params = array(':userid' => $_SESSION['userid']);
            $this->set('localusers', $this->Account->query('SELECT a2.* FROM Account a1 JOIN Account a2 ON (a1.LocationID = a2.LocationID) WHERE a1.id = :userid)', $params)); 
        }
        
        function findbycategory(){

        }

        function searchresults(){
            if(isset($_GET["category"])){
                $category = $_GET["category"];
                $params = array(':category' => $category);
                $this->set('items', $this->Item->query('SELECT Item.* FROM Item JOIN Category ON (Category.id = Item.CategoryID) WHERE Category.Category = :category', $params));
            }
            if(isset($_GET["userid"])){
                $userid = $_GET["userid"];
                $params = array(':userid' => $userid);
                $this->set('items', $this->Item->query('SELECT Item.* FROM Item JOIN Account ON (Item.LenderID = Account.id) WHERE Account.id = :userid', $params));
            }
        }
    }
?>