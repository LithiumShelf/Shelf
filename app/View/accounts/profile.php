<?php

	/*Page template for both user's profile and other people's profile.
	*/
$user = $user[0];
$lent = $user["Lent"];
$borrowed = $user["Borrowed"];
?>

<div id="profile_container">
	<section id="info">
		<h1><?= $user["firstName"]." ".$user["lastName"]?></h1>
		<h2><?= $user["Username"]?></h2>
		
		<?php
			if(isset($user["profilePic"])){
				$fullsize = explode('.', $user["profilePic"]);
				$ext = array_pop($fullsize);
				$thumbnail = implode('.', array_push($fullsize, "_thumb", $ext));
		?>
		<img src="<?= $BASE_URL ?>/images/profile/<?= $thumbnail ?>" alt="<?= $user['Username']?>">
		<?php } ?>
		
		
		<p>
			<?php if($lent + $borrowed == 0){
				echo "0%";
				}else{ ?>
			<?= round(100*($user["numSuccessful"]/($lent + $borrowed)), 0) ?>% Reputation<br>			
			<?php } ?>
			Lent <?= $lent ?> times | Borrowed <?= $borrowed ?> times
		</p>
	</section>
	
	<?php if($user['id'] != $_SESSION['userid']) {?>
	<section id="actions">
		<button class="lend" type="button" name="offer">Offer an item</button>
		<button type="button" name="message">Send a message</button>
	</section>
	<?php }?>

	
	<section id="inventory">
		<h1>Inventory</h1>
		<!--<a href="searchresults?userid=<?= $user['id']?>" class="ui-btn ui-corner-all">Search Inventory</a>-->

	</section>
	
	<section id="reviews">
		<h1>Reviews</h1>
		
	</section>
</div>



<?php function printUser($user){
	$lent = $user["Lent"];
	$borrowed = $user["Borrowed"];
?>
	<a href="searchresults?userid=<?= $user['id']?>" class="ui-btn ui-corner-all">
		<div class="user">
			<?php
			if(isset($user["profilePic"])){
				$fullsize = explode('.', $user["profilePic"]);
				$ext = array_pop($fullsize);
				$thumbnail = implode('.', array_push($fullsize, "_thumb", $ext));
			?>
			<img src="<?= $BASE_URL ?>/images/profile/<?= $thumbnail ?>">
			<?php } ?>
			<h3><?= $user["Username"] ?></h3>
			<h4><?= $user["firstName"] . " " . $user["lastName"] ?></h4>
			<p>
				Level <br>
				<?php if($lent + $borrowed == 0){
					echo "0%";
				    }else{ ?>
				<?= round(100*($user["numSuccessful"]/($lent + $borrowed)), 0) ?>% Reputation<br>			<?php } ?>
				Lent <?= $lent ?> times | Borrowed <?= $borrowed ?> times
			</p>
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