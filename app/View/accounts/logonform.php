<?php

    if(!empty($_SESSION["userid"])){
            echo "You are already logged in";
    }
    <form action="./login" method="post">
    input  name = "username"
    input name = passhash
    input type=submit
    </form>

?>