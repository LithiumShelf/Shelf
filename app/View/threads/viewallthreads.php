<?php
//CASE SWITCH FOR FEED/BORROW/LEND
switch ($type) {
    case "feed":
		foreach ($threads as $thread) { 
			$itemname = $thread['Name'];
			$src = $thread['ItemPic'];
			$person = $thread['firstName']." ".$thread['lastName'];
			$asin = $thread['ASIN'];
		 ?>
		<li>
			<!--Feed Tile: Top-->
			<div style="float: left;"> 
				<a href="more/accounts/profile/<?= $thread['LenderID']?>">
					<img src="<?= $thread['profilePic'] ?>" style="float: left; width: 50px; height: 50px;">
				</a>
			</div>
			
			<p><strong><?=$person?></strong> is borrowing <strong><?=$itemname ?></strong>.</p>
			
			<!--Feed Tile: Image-->
			<a href="borrow/items/searchresults?search_item=<?=$itemname?>">
				<img src="<?=$src?>" alt="<?=$itemname?>">
			</a>
			
			<!--Feed Tile: Bottom-->
			<button type=submit" value="<?=$itemname?>" onclick="borrow/items/searchresults?search_item=<?=$itemname?>" style="clear: left;">Find a similar item</button>
		</li>
		
		<?php
		}
        break;
		
    case "borrow":
		//LOOP: Counter for when to separate Currently Requested from Borrowing, (fencepost)
		$newsection = 0; 
		$currentstat = "Open";
		foreach ($threads as $thread) { 
			$status = $thread['ThreadStatus'];
			$due = $thread['DueDate'];
			$notPending = $status == "Current" || $status =="Closed" || $status == "Rejected" || $status =="Late" || $status == "Failed";
		//HEADER: If transition from Currently Requested to Borrowing, increment counter
			if($newsection == 0 && $notPending ){  
				$newsection++; 
				$currentstat="Closed";?>
				</ul>
				<h1>Borrowing</h1>
				<ul>
			<?php } ?>
		
			<li class="<?= $currentstat?>">
				<div style="float: left;">
					<a href="more/accounts/profile/<?= $thread['LenderID']?>">
						<img src="<?= $thread['profilePic'] ?>" style="float: left; width: 50px; height: 50px;">
					</a>
				</div>
				
			<!--PLACEHOLDER: Link to thread on click-->
				<a href="http://www.google.com#q=thread">
					<div >
						<!--General-->
						<strong><?= $thread['Name'] ?></strong> from <strong><?= $thread['firstName']." ".$thread['lastName'] ?></strong> <br> 
						<!--Reputation-->
						Borrowed <?= $thread['Borrowed'] ?> Lent <?= $thread['Lent'] ?> <br>
						<!--Status-->
						<?= $status ?>
					</div>
				</a>
				
			<!--NEXT STEPS-->
				<div style = "clear:left;">
					<?php
						if($status=="Open"|| $status=="Current" ) {
					?>
					<button type="button" name="return">Return</item> 
					<?php  } else if ($status=="Requested" ) { 
					?>

					<button type="button" name="" formaction="">Receive</button>
					<button type="button" name="" formaction="">Cancel</button>
					<?php } else {
					} ?>
				</div>
			</li>
		<?php 
			}
		break;
		
    case "lend":
		//Counter to determine new section header (fencepost)
		$newsection = 0; 
		foreach ($threads as $thread) { 
			$status = $thread['ThreadStatus'];
			$due = $thread['DueDate'];
			if ($newsection == 0 && $status == "Closed") {
				$newsection++;?>
				</ul> <br>
				<h1>Past Lending</h1><br>
				<ul>
			<?php }
			?>
			<li class="<?= $status ?>">
				<div style="float:left;">
					<a href="more/accounts/profile/<?= $thread['LenderID']?>">
						<img src="<?= $thread['profilePic'] ?>" style="float: left; width: 50px; height: 50px;">
					</a>
				</div>
				
			<!--PLACEHOLDER: Link to thread on click-->
				<a href="http://www.google.com#q=thread">
					<div style="float: left;">
						<!--General-->
						<strong><?= $thread['firstName']." ".$thread['lastName'] ?></strong> - <strong><?= $thread['Name'] ?></strong>  <br> 
						<!--Reputation-->
						Borrowed <?= $thread['Borrowed'] ?> Lent <?= $thread['Lent'] ?> <br>
						<br>
						<!--Latest Message-->
						
					</div>
				</a>	
			</li>
		<?php }
        break;
}?>
