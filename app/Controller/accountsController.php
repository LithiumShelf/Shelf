<?php
class accountsController extends controller {
        function addfriend($id = null){
            //add a new friend
            //check if the user already sent you a friend request
            $this->set('accept',$this->Account->query(''));
            //send the user a new friend request
            $this->set('request', $this->Account->query(''));
        }
        
        function login(){
                // find your account and persist that id number storing it in the sessions
                // http://www.php.net/manual/en/session.examples.basic.php
                $params = array(':username' => $_POST['username'],
                                ':passhash' => $_POST['passhash']);
                $this->('user', $this->Account->query('SELECT * FROM :thistable WHERE Username = :username AND passhash = :passhash');
        }
        
        function logonform(){
                // page for the user to logon for register
        }
        
        function profile($id){
            // view the profile of a user by the Account table ID
                $this->set('user', $this->Account->query('SELECT * FROM :thistable JOIN Reputation ON (Account.id = Reputation.id) WHERE id = :id');
        }
        
        function register(){
                //add a new account to Accounts table
                //generate passhash using sha256 | $hashedPW = hash('sha256', $saltedPW);
                $params = array(':fname' => $_POST['fname'],
                                ':lname' => $_POST['lname'],
                                ':username' => $_POST['username'],
                                ':passhash' => $_POST['passhash']);
                //Only location is ID 1: Seattle WA
                $this->set('register', $this->Account->query('INSERT INTO :thistable (Username, firstName, lastName, passhash, LocationID) values (:username, :fname, :lname, :passhash, 1)'));
        }
        
        function friends(){
                $params = array(':userid' => $_SESSION['userid'])
                $this->set('friends', $this->Account->query('SELECT Account.* FROM Account JOIN Friend ON (Friend.Friend = Account.id)'));
        }
        
        function local(){
                $this->set('localusers', $this->Account->query('SELECT * FROM Account WHERE localid = 1'); 
        }
}

?>