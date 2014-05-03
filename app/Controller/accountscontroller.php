<?php
class accountsController extends Controller {
        function addfriend(){
            //add a new friend
            $params = array(':userid' => $_SESSION['userid'],
                            ':friendid' => $_POST['id']);
            //check if the user already sent you a friend request
        ////$this->set('accept',$this->Account->query(''), $params);
            //send the user a new friend request
            $this->set('request', $this->Account->query('INSERT INTO Friend (:userid, :friendid)', $params));
        }
        
        function login(){
                // find your account and persist that id number storing it in the sessions
                // http://www.php.net/manual/en/session.examples.basic.php
                $params = array(':username' => $_POST['username'],
                                ':passhash' => $_POST['passhash']);
                $this->set('user', $this->Account->query('SELECT * FROM Account WHERE Username = :username AND passhash = :passhash', $params));
        }
        
        function logonform(){
                // page for the user to logon for register
        }
        
        function profile($id){
            // view the profile of a user by the Account table ID
                $this->set('user', $this->Account->query('SELECT * FROM Account JOIN Reputation ON (Account.id = Reputation.id) WHERE id = :id', $params));
        }
        
        function register(){
                //add a new account to Accounts table
                //generate passhash using sha256 | $hashedPW = hash('sha256', $saltedPW);
                $hashedpass = hash (SHA256,$_POST['passhash'])
                $params = array(':fname' => $_POST['fname'],
                                ':lname' => $_POST['lname'],
                                ':username' => $_POST['username'],
                                ':passhash' => $hashedpass);
                //Only location is ID 1: Seattle WA
                $this->set('register', $this->Account->query('INSERT INTO Account (Username, firstName, lastName, passhash, LocationID) VALUES (:username, :fname, :lname, :passhash, 1)', $params));
        }
        
        function friends(){
                $params = array(':userid' => $_SESSION['userid']);
                $this->set('friends', $this->Account->query('SELECT Account.* FROM Account JOIN Friend ON (Friend.Friend = Account.id)', $params));
        }
        
        function local(){
                $this->set('localusers', $this->Account->query('SELECT * FROM Account WHERE localid = 1', $params)); 
        }
        
        function uploadprofileimg(){
                //http://www.w3schools.com/php/php_file_upload.asp
                $allowedExts = array("gif", "jpeg", "jpg", "png");
                $temp = explode(".", $_FILES["file"]["name"]);
                $extension = end($temp);
                if ((($_FILES["file"]["type"] == "image/gif")
                        || ($_FILES["file"]["type"] == "image/jpeg")
                        || ($_FILES["file"]["type"] == "image/jpg")
                        || ($_FILES["file"]["type"] == "image/pjpeg")
                        || ($_FILES["file"]["type"] == "image/x-png")
                        || ($_FILES["file"]["type"] == "image/png"))
                        && ($_FILES["file"]["size"] < 1024000)
                        && in_array($extension, $allowedExts)) {
                                if ($_FILES["file"]["error"] > 0) {
                                        echo "Error: " . $_FILES["file"]["error"] . "<br>";
                                } else {
                                        echo "Upload: " . $_FILES["file"]["name"] . "<br>";
                                        echo "Type: " . $_FILES["file"]["type"] . "<br>";
                                        echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
                                        $path = ROOT . DS . 'webroot' . DS . 'images' . DS . 'profile' . DS;
                                        if (file_exists($path . $_FILES["file"]["name"])) {
                                                echo $_FILES["file"]["name"] . " already exists. ";
                                         } else {
                                                //save file to directory
                                                move_uploaded_file($_FILES["file"]["tmp_name"],
                                                $path .  $_FILES["file"]["name"]);
                                                //run imageMagic to make thumbnail of the new file at file_thumb.ext
                                                 echo "Stored in: " . $path . $_FILES["file"]["name"];
                                                 //replace $_FILES["file"]["name"] with the newly saved filename
                                                $params = array(':userid' => $_SESSION['userid'],
                                                                ':filepath' => 'profile' . DS . $_FILES["file"]["name"]));
                                                $this->set('file', $this->Account->query('UPDATE Account SET profilePic = :filepath WHERE id = :userid', params));
                                         }
                                }
                        }else {
                                echo "Invalid file";
                        }
                }
        }

?>