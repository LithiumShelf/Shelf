<?php
	/*List of friends*/
?>
<div id="friend_container">
	<h1>Friend Requests</h1>
	<?php
        if($requests){
            foreach ($requests as $request){
                printuser($request, true);
            }
        }else{
            echo "You have no Friend Requests";
        }
        ?>
</div>

<div id="main">
	<h1>Your friends</h1>
	<?php
        if($friends){
            foreach ($friends as $friend){
                printuser($friend, false);
            }
        }else{
            echo "You have no Friends";
        }
        ?>
</div>

<?php
    function printuser($user, $request){
?>
    <a href="profile/<?= $user["id"] ?>">
    <div class="user">
        <?php if (isset($user['profilepic'])){
            $pic = $user['profilepic'];
            $ext = array_pop(explode('.', $pic));
            $thumb = $pic . '_thumb' . $ext;
            ?>
            <a href="<?= $BASE_URL ?>/webroot/<?= $user['profileimg'] ?>">
            <img src="<?= $BASE_URL ?>/webroot/<?= $thumb ?>"
                                                  title="<?= $user['Username']?>">
            </a>
        <?php }
        $b = $user['Borrowed'];
        $l = $user['Lent'];
        if($b + $l == 0){
            $pSuccess =  0;
        }else{
            $pSuccess = round(($user['numSuccessful'] / ($b + $l)) * 100, 2);
        }
        ?>
        <h3><?= $user['Username'] ?></h3>
        <p><?= $user['firstName'] ?> <?= $user['lastName'] ?></p>
        <br />
       Borrowed: <?= $b ?> times <br />
       Lent: <?= $l ?> times <br />
       Percent successful: <?= $pSuccess ?>% <br />
       <?php
            if($request){
       ?>
       <button class="addFriend" type="button" name="id" value="<?= $user["id"] ?>">Add as friend</button>
       <?php
            }
       ?>
    </div>
    </a>
    
<?php
}
?>

<script>
    $("button.addFriend").click(function(){
        friend = $(this).val();
        $.post("/ajax/accounts/addfriend",{id:friend},function(data,status){
            if (status == "success") {
                $(this).text("Success");
            }
        });
    }); 
</script>