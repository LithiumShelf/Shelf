<?php
    if(isset($_SESSION["userid"])){
            echo "You are already logged in";
    }else{
    ?>
        <form action="login" method="post">
        <h2>Login</h2>
        Username: <input input type="text" name="username">
        Password: <input type="password" name="password">
        <!--<input type=submit value="Login">-->
        <button type="submit">Login</button>
        </form>
        
        <form action="register" method="post">
        <h2>Register</h2>
        Username: <input input type="text" name="username">
        First Name: <input input type="text" name="fname">
        Last Name: <input input type="text" name="lname">
        Location: <select name="location">
        <?php
            foreach ($locations as $location){
        ?>
            <option value="<?=  $location['id'] ?>"><?= $location['Region'] . ", " . $location['State'] ?></option>
        <?php
            }
        ?>
        </select>
        Password: <input type="password" name="password">
        <!--<input type=submit value="Register">-->
        <button type="submit">Register</button>
        </form>
<?php
    }
?>