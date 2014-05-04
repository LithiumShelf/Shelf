<?php
    //echo $_POST['password'];
    //echo $_POST['username'];
    //echo print_r($user);
    //print_r($user);
     if($user){
        //session_start();
        $_SESSION["userid"] = $user[0]["id"];
        echo "You are now logged in"
     }else{   echo "Wrong Username or Password";
     }
    
    //then start the session and redirect to /feed/
    
?>