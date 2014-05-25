<?php
    //echo $_POST['password'];
    //echo $_POST['username'];
    //echo print_r($user);
    //print_r($user);
     if($user){
        //session_start();
        $_SESSION["userid"] = $user[0]["id"];
        echo "You are now logged in ";
        echo '<a href="/" data-ajax="false">Click here </a>';
        echo "to go home, if not you'll be redirected in 10 seconds";
     }else{   echo "Wrong Username or Password";
     }
    
    //then start the session and redirect to /feed/
    
?>

<script>
var redirTime = "10000";
var redirURL = "http://www.dontbeshelfish.com";
$( document ).ready(redirTimer);
function redirTimer() { self.setTimeout("self.location.href = redirURL;",redirTime); }
</script>