<?php

?>

<div id="main">
	<h1>Local People in your Area</h1>
	<?php
        foreach ($localusers as $user){
        ?>
            <div class="user">
                <?php if(isset($user['profilepic'])){
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
                    $pSuccess = round($user['numSuccessful'] / ($b + $l), 2);
                }
                
                ?>
                <h3><?= $user['Username'] ?></h3>
                <p><?= $user['firstName'] ?> <?= $user['lastName'] ?></p>
                <br />
                Borrowed: <?= $b ?> times <br />
                Lent: <?= $l ?> times <br />
                Percent successful: <?= $pSuccess ?>% <br />
               <button class="addFriend" type="button" name="id" value="<?= $user["id"] ?>">Add as friend</button>
            </div>
        <?php
        }
        ?>
</div>

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