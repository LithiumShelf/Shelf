<p>

<?php

//print_r($_POST);

if($register){
    echo '<a href="uploadprofileimg"> Do you want to upload a profile picture? (You can always do this later) </a>';
?>
</p>
    <p><a href="logonform">Click here to Login</a></p>
<?php
}else{
    echo 'It seems that something has gone wrong. Please contact the systems administrator, whoever that may be.';
}



?>