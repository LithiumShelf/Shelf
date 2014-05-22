<?php

	/*Page template for both user's profile and other people's profile.
	*/
$inventory = $user;
$user = $inventory[0];
$lent = $user["Lent"];
$borrowed = $user["Borrowed"];
$src = $user["profilePic"];
$name = $user["firstName"]." ".$user["lastName"];
?>
	<style scoped>
		div > div{background-color: #DDDDDD !important;
    	color: #000000 !important;}
	</style>
<div id="profile_container">

	<div id="info">
		<h1><?= $name?></h1>
		<h2><?= $user["Username"]?></h2>
		
		<?php
			/*if(isset($user["profilePic"])){
				$fullsize = explode('.', $user["profilePic"]);
				$ext = array_pop($fullsize);
				$thumbnail = implode('.', array_push($fullsize, "_thumb", $ext));*/
			if (!$src) {
				$src = "default.jpg";
			}
		?>
		<img src="http://www.dontbeshelfish.com/images/profile/<?= $src ?>" alt="<?= $name?>" style="width:50%; margin-left:25%; margin-right:50%;">
		<?php //} ?>
		
		
		<h7>
			<?php if($lent + $borrowed == 0){
				echo "0%";
				}else{ ?>
			<?= round(100*($user["numSuccessful"]/($lent + $borrowed)), 0) ?>% Reputation<br>			
			<?php } ?>
			Lent <?= $lent ?> times | Borrowed <?= $borrowed ?> times
		</h7>
	</div>
	
	<?php if($user['UserID'] != $_SESSION['userid']) {?>
	<div id="actions">
		<button class="lend" type="button" name="offer">Offer an item</button>
		<button type="button" name="message">Send a message</button>
		<button class="addFriend" type="submit">Add Friend</button>
	</div>
	<?php }?>

	
	<div id="inventory">
		<h1>Inventory</h1>
		<!--<a href="searchresults?userid=<?= $user['id']?>" class="ui-btn ui-corner-all">Search Inventory</a>-->
		<?php 
			foreach ($inventory as $item) {
				$itemname = $item['Name'];
				if (!$itemname) {
					$itemname = "Unnamed Item";
				}
				
		?> 
			<a href="http://www.dontbeshelfish.com/lend/items/itempage/<?= $item['ItemID']?>">
				<strong><?= $itemname ?></strong>  
			</a>
			<br>
			
		<?php
			}	
		?>
	</div>
	
	<div id="reviews">
		<h1>Reviews</h1>
		
	</div>
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