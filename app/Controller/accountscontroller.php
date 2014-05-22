<?php
class accountsController extends Controller {
        function addfriend(){
                if(isset($_POST['id'])){
                    //add a new friend
                        $params = array(':userid' => $_SESSION['userid'],
                            ':friendid' => $_POST['id']);
                    //check if the user already sent you a friend request
                ////$this->set('accept',$this->Account->query(''), $params);
                    //send the user a new friend request, or accepts an existing request.
                    $this->set('request', $this->Account->query('INSERT INTO Friend (:userid, :friendid)', $params));
                }else{
                        
                }
        }
        
        function login(){
                // find your account and persist that id number storing it in the sessions
                // http://www.php.net/manual/en/session.examples.basic.php
                $hashedpass = hash('SHA256',$_POST['password']);
                $params = array(':username' => $_POST['username'],
                                ':passhash' => $hashedpass);
                $this->set('user', $this->Account->query('SELECT * FROM Account WHERE Username = :username AND passhash = :passhash', $params));
        }
        
        function logonform(){
                // page for the user to logon for register
                $this->set('locations', $this->Account->query('SELECT * FROM Location', null));
        }
        
        function profile($id){
                $this->set('id', $id);
                $params = array(':id' => $id);
            // view the profile of a user by the Account table ID
                $this->set('user', $this->Account->query('SELECT *, Item.id as ItemID, Account.id as UserID FROM Account JOIN Item ON (Account.id = Item.LenderID) WHERE LenderID = :id', $params));
        }
        
        function register(){
                //add a new account to Accounts table
                //generate passhash using sha256 | $hashedPW = hash('sha256', $saltedPW);
                $hashedpass = hash('SHA256',$_POST['password']);
                $params = array(':fname' => $_POST['fname'],
                                ':lname' => $_POST['lname'],
                                ':username' => $_POST['username'],
                                ':passhash' => $hashedpass,
                                ':location' => $_POST['location']);
                //Only location is ID 1: Seattle WA
                $this->set('register', $this->Account->query('INSERT INTO Account (Username, firstName, lastName, passhash, LocationID, Lent, Borrowed, numSuccessful)
                                                             VALUES (:username, :fname, :lname, :passhash, :location, 0, 0, 0)', $params));
        }
        
        function friends(){
                //Check Requests (return AccountID of users who have requested friendship)
                $params = array(':userid' => $_SESSION['userid']);
                $this->set('requests', $this->Account->query('SELECT id FROM Account JOIN Friend ON (Friend.User = Account.id) 
                        WHERE Friend.Friend = :userid AND Friend.User NOT IN (SELECT Friend.Friend FROM Friend WHERE Friend.User = :userid)', $params));
                //List out confirm friends JOINed with Accounts
                //$params = array(':userid' => $_SESSION['userid']);
                $this->set('friends', $this->Account->query('SELECT * FROM loztwodc_shelf.friends WHERE User = :userid;)', $params));
        }
        
        function local(){
                //find hot item owners in your area
                $params = array(':userid' => $_SESSION['userid']);
                $this->set('localusers', $this->Account->query('SELECT a2.* FROM Account a1 JOIN Account a2 ON (a1.LocationID = a2.LocationID) WHERE a1.id = :userid)', $params)); 
        }
        
        function uploadprofileimg(){
                if($_FILES){
                //http://www.w3schools.com/php/php_file_upload.asp
                $allowedExts = array("gif", "jpeg", "jpg", "png");
                $temp = explode(".", $_FILES["profileimg"]["name"]);
                $extension = end($temp);
                if ((($_FILES["profileimg"]["type"] == "image/gif")
                        || ($_FILES["profileimg"]["type"] == "image/jpeg")
                        || ($_FILES["profileimg"]["type"] == "image/jpg")
                        || ($_FILES["profileimg"]["type"] == "image/pjpeg")
                        || ($_FILES["profileimg"]["type"] == "image/x-png")
                        || ($_FILES["profileimg"]["type"] == "image/png"))
                        && ($_FILES["profileimg"]["size"] < 1024000)
                        && in_array($extension, $allowedExts)) {
                                if ($_FILES["profileimg"]["error"] > 0) {
                                        echo "Error: " . $_FILES["profileimg"]["error"] . "<br>";
                                } else {
                                        echo "Upload: " . $_FILES["profileimg"]["name"] . "<br>";
                                        echo "Type: " . $_FILES["profileimg"]["type"] . "<br>";
                                        echo "Size: " . ($_FILES["profileimg"]["size"] / 1024) . " kB<br>";
                                        $path = ROOT . DS . 'webroot' . DS . 'images' . DS . 'profile' . DS;
                                        if (file_exists($path . $_FILES["profileimg"]["name"])) {
                                                echo $_FILES["profileimg"]["name"] . " already exists. ";
                                         } else {
                                                //save file to directory
                                                move_uploaded_file($_FILES["profileimg"]["tmp_name"],
                                                $path .  $_FILES["profileimg"]["name"]);
                                                //run imageMagic to make thumbnail of the new file at file_thumb.ext
                                                
                                                //thumbnail image created, write to directory
                                                $imagethumbnailpath = findThumbnailPath($_FILES["profileimg"]["name"]."_thumb.jpg");
                                                $imagethumbnail = new imagick($imagethumbnailpath);
                                                $imagethumbnail->cropThumbnailImage(150, 150);
                                                $imagethumbnail->setImageFormat("png");
                                                header("Content-Type: image/png");
                                                //$imagethumbnail->writeImage


                                                //bool Imagick::cropThumbnailImage ()

                                                 echo "Stored in: " . $path . $_FILES["profileimg"]["name"];
                                                 //replace $_FILES["file"]["name"] with the newly saved filename
                                                $params = array(':userid' => $_SESSION['userid'],
                                                                ':filepath' => 'profile' . DS . $_FILES["profileimg"]["name"]);
                                                $this->set('file', $this->Account->query('UPDATE Account SET profilePic = :filepath WHERE id = :userid', params));
                                         }
                                }
                        }else {
                                echo "Invalid file";
                        }
                }
                }
        }

?>