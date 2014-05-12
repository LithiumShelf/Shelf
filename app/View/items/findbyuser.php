<?php

?>

<div id="by_friend_container">
	<h1>Browse by friend</h1>
	<?php
		if(!$friends){
			echo "You don't have any friends... Invite your friends and add them! Or go and accept outstanding friend invites";
		}else{
			foreach($friends as $friend){
				printUser($user);
			}
		}
	?>
	<!--id= this user, get all ppl that are connected with this user-->
	<!--when click on person, send request for items belonging to that person-->
</div>

<div id="by_local_container">
	<h1>Browse by local users</h1>
	<?php
		if(!$localusers){
			echo "You're the first person to use Shelf in your area";
		}else{
			foreach($localusers as $user){
				printUser($user);
			}
		}
?>
</div>
<?php
function printUser($user){
	$lent = $user["Lent"];
	$borrowed = $user["Borrowed"];
?>
	<a href="searchresults?userid=<?= $user["id"] ?> " class="ui-btn ui-corner-all">
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
				<?php if($b + $l == 0){
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
	<!--id= this user, get all ppl that are connected with this user-->
	<!--when click on person, send request for items belonging to that person-->
