<?php

?>

<div id="main">
	<h1>Local People in your Area</h1>
	<?php
        foreach (localusers as $user){
        ?>
            <div class="user">
                <?php if isset($user['profilepic']){
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
                $pSuccess = $user['numSuccessful'] / ($b + $l)
                ?>
                <h3><?= $user['Username'] ?></h3>
                <p><?= $user['firstName'] ?> <?= $user['lastName'] ?></p>
                <br />
                Borrowed: <?= $b ?> times <br />
                Lent: <?= $l ?> times <br />
                Percent successful: <?= $pSuccess ?>% <br />
            </div>
        <?php
        }
        ?>
</div>