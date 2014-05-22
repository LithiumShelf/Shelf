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
            //print_r($_POST);
            if(isset($_POST["product"])){
                $product = explode("|", $_POST["product"]);
                $params = array(':cat' => $product[2],
                                ':catplural' => $product[2] + "s");
                $productGroup = $this->Item->query('SELECT * FROM Category WHERE Category = :cat OR Category = :catplural', $params);
                if(!isset($productGroup[0])){
                    $params = array(':cat' => $product[2]);
                    $productGroup = $this->Item->query('INSERT INTO Category VALUES(null, :cat)', $params);
                }else{
                    $productGroup = $productGroup[0]["id"];
                }
                if(isset($product[3])){
                    $listprice = $product[3];
                }else{
                    $listprice = null;
                }
                $params = array(':userid' => $_SESSION["userid"],
                                ':asin' => $product[0],
                                ':name' => $product[1],
                                ':category' => $productGroup,
                                ':picURL' => null/*$_POST['picurl']*/,
                                ':status' => "Available",
                                ':listprice' => $listprice);
                $this->set('inventory', $this->Item->query('INSERT INTO Item VALUES (null, :asin, :category, :name, :picURL, :status, :userid, :listprice)', $params));
            }
        }
        
        function removefromlending(){
            $params = array(':id' => $_POST["id"],
                            ':userid' => $_SESSION["userid"]);
            $this->set('inventory', $this->Item->query('DELETE FROM Item WHERE LenderID = :userid AND id = :id', $params));
        }
        
        function itempage($id){
            $this->set('item', $this->Item->selectjoinaccount($id));
        }
        
        function findbyuser(){
            //Get Friends
            $params = array(':userid' => $_SESSION['userid']);
            $this->set('friends', $this->Item->getFriends($params));
            //Get Local
            //find hot item owners in your area
            $this->set('localusers', $this->Item->getLocalUsers($params)); 
        }
        
        function findbycategory(){
            $this->set('cats', $this->Item->getCategories());
        }

        function searchresults(){
            $params = array(':userid' => $_SESSION['userid']);
            $this->set('cats', $this->Item->getCategories());
            $this->set('friends', $this->Item->getFriends($params));
            $this->set('localusers', $this->Item->getLocalUsers($params));
            $query = 'SELECT Item.* FROM Item JOIN Category ON (Category.id = Item.CategoryID)
                            JOIN Account ON (Item.LenderID = Account.id) ';
            if(isset($_GET["category"]) && isset($_GET["userid"]) && $_GET["category"] != "" && $_GET["category"] !=""){
                $category = $_GET["category"];
                $userid = $_GET["userid"];
                $params = array(':category' => $category,
                                ':userid' => $userid);
                $query .= 'WHERE Category.id = :category AND Account.id = :userid';
            }else{
                if(isset($_GET["category"]) && $_GET["category"] != ""){
                    $category = $_GET["category"];
                    $params = array(':category' => $category);
                    $query .= 'WHERE Category.id = :category';
                }
                if(isset($_GET["userid"]) && $_GET["userid"] != ""){
                    $userid = $_GET["userid"];
                    $params = array(':userid' => $userid);
                    $query .= 'WHERE Account.id = :userid';
                }else{
                    $params[':youruserid'] = $_SESSION['userid'];
                    $query .= ' WHERE Account.id != :youruserid';
                }
            }
            if(isset($_GET["pricerange"]) && !empty($_GET["pricerange"]) ){
                $pricebounds = explode('-', $_GET["pricerange"]);
                $params[':min'] = $pricebounds[0];
                $params[':max'] = $pricebounds[1];
                $query .= ' AND ListPrice > :min AND ListPrice > :max';
            }

            $this->set('items', $this->Item->query($query, $params));
        }
    }
?>