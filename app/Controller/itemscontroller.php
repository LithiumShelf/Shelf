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
                
                if($_FILES){
                //http://www.w3schools.com/php/php_file_upload.asp
                $allowedExts = array("gif", "jpeg", "jpg", "png");
                $temp = explode(".", $_FILES["img"]["name"]);
                $extension = end($temp);
                if ((($_FILES["img"]["type"] == "image/gif")
                        || ($_FILES["img"]["type"] == "image/jpeg")
                        || ($_FILES["img"]["type"] == "image/jpg")
                        || ($_FILES["img"]["type"] == "image/pjpeg")
                        || ($_FILES["img"]["type"] == "image/x-png")
                        || ($_FILES["img"]["type"] == "image/png"))
                        && ($_FILES["img"]["size"] < 2048000)
                        && in_array($extension, $allowedExts)) {
                                if ($_FILES["img"]["error"] > 0) {
                                        echo "Error: " . $_FILES["img"]["error"] . "<br>";
                                } else {
                                        echo "Upload: " . $_FILES["img"]["name"] . "<br>";
                                        echo "Type: " . $_FILES["img"]["type"] . "<br>";
                                        echo "Size: " . ($_FILES["img"]["size"] / 1024) . " kB<br>";
                                        $path = ROOT . DS . 'webroot' . DS . 'images' . DS . 'item' . DS;
                                        if (file_exists($path . $_FILES["img"]["name"])) {
                                                echo $_FILES["profileimg"]["name"] . " already exists. ";
                                                die('Item NOT put up for lending, please go back and try again');
                                         } else {
                                                //save file to directory
                                                move_uploaded_file($_FILES["img"]["tmp_name"],
                                                $path .  $_FILES["img"]["name"]);
                                                 echo "Uploaded Image Stored in: " . $path . $_FILES["img"]["name"];
                                                $picurl = 'http://' . $_SERVER['HTTP_HOST'] . '/' . 'webroot' . '/' . 'images' . '/' . 'item' . '/' . $_FILES["img"]["name"];
                                         }
                                }
                        }else {
                                //echo "Invalid file";
                                $picurl = $product[4];
                        }
                }else{
                    $picurl = $product[4];
                }
                $params = array(':userid' => $_SESSION["userid"],
                                ':asin' => $product[0],
                                ':name' => $product[1],
                                ':category' => $productGroup,
                                ':picURL' => $picurl,
                                ':status' => "Available",
                                ':listprice' => $listprice);
                $this->set('inventory', $this->Item->query('INSERT INTO Item VALUES (null, :asin, :category, :name, :picURL, :status, :userid, :listprice)', $params));
            }
        }
        
        function removefromlending(){

            try{
                $this->Item->beginTransaction();
                $params = array(':itemid' => $_POST["id"]);
                $this->Item->query('DELETE FROM Thread WHERE ItemID = :itemid', $params);
                $params = array(':id' => $_POST["id"],
                            ':userid' => $_SESSION["userid"]);
                $this->Item->query('DELETE FROM Item WHERE LenderID = :userid AND id = :id', $params);
                $this->Item->commit();
                echo "Success";
            }catch(Exception $e){
                $this->Item->rollBack();
                echo "Failed";
            }
            
        }
        
        function itempage($id){
            $this->set('item', $this->Item->selectjoinaccount($id));
            $this->set('itemID',($id));
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
            if(isset($_GET["category"]) && isset($_GET["userid"]) && $_GET["category"] != "" && $_GET["userid"] !=""){
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
                    if(isset($_GET["category"]) && $_GET["category"] != ""){
                        $params[':youruserid'] = $_SESSION['userid'];
                        $query .= ' AND Account.id != :youruserid';
                    }else{
                        $params[':youruserid'] = $_SESSION['userid'];
                        $query .= 'WHERE Account.id != :youruserid';
                    }
                    
                }
            }
            if(isset($_GET["pricerange"]) && !empty($_GET["pricerange"]) && $_GET["pricerange"] != ""){
                $pricebounds = explode('-', $_GET["pricerange"]);
                $params[':min'] = $pricebounds[0];
                $params[':max'] = $pricebounds[1];
                $query .= ' AND ListPrice > :min AND ListPrice > :max';
            }
            $this->set('items', $this->Item->query($query, $params));
        }
        
        function myitems(){
            $params = array(':id' => $_SESSION['userid']);
            $this->set('inventory', $this->Item->query('SELECT *, Item.id as ItemID, Account.id as UserID FROM Account JOIN Item ON (Account.id = Item.LenderID) WHERE LenderID = :id', $params));
        }
    }
?>