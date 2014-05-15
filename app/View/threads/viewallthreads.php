<?php
//CASE SWITCH FOR FEED/BORROW/LEND
switch ($type) {
    case "feed":
		foreach ($threads as $thread) { 
			$itemname = $thread['Name'];
			$src = $thread['ItemPic'];
			$person = $thread['bFirst']." ".$thread['bLast'];
			$asin = $thread['ASIN'];
		 ?>
		<li>
			<!--Feed Tile: Top-->
			<div style="float: left;"> 
				<a href="more/accounts/profile/<?= $thread['BorrowerID']?>">
					<img src="<?= $BASE_URL ?>/images/profile/<?= $thread['profilePic']?>" alt="<?=$person?>" style="float: left; width: 50px; height: 50px;">
				</a>
			</div>
			
			<p><strong><?=$person?></strong> is borrowing <strong><?=$itemname ?></strong>.</p>
			
			<!--Feed Tile: Image-->
			<a href="borrow/items/searchresults?search_item=<?=$itemname?>">
					<img src="<?= $BASE_URL ?>/images/item/<?=$src?> ?>" alt="<?=$itemname?>">
			</a>
			
			<!--Feed Tile: Bottom-->
			<button type=submit" value="<?=$itemname?>" onclick="window.location='borrow/items/searchresults?search_item=<?=$itemname?>'" style="clear: left;">Find a similar item</button>
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
			$done = $status =="Closed" || $status == "Rejected" || $status =="Late" || $status == "Failed";
		//HEADER: If transition from Currently Requested to Borrowing, increment counter
			if($newsection == 0 && $status=="Current" ){  
				$newsection++; 
				?>	
				</ul>
				<h1>Borrowing</h1>
				<ul>
			<?php } else if ($newsection <= 1 && $done) { 
				$newsection= 2;
				$currentstat="Closed";
				?>
				</ul>
				<div data-role="collapsible">
					<h1>Borrow History</h1>
					<ul>
			<?php 
				}
			?>
			
			
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
						<?= $due ?>
					</div>
				</a>
				
			<!--BUTTONS-->
				<div style = "clear:left;">
					<?php
						if($status=="Open"|| $status=="Current" ) {
					?>
					<button type="button" name="current">Return</item> 
					<?php  } else if ($status=="Requested" ) { 
					?>

					<button type="button" name="requested" value="complete">Receive</button>
					<button type="button" name="requested" value="failed">Cancel</button>
					<?php } else {
					} ?>
				</div>
			</li>
		<?php }
		if ($newsection > 1) {
		?>
		</div>
		<?php }
		break;
		
    case "lend":
		//Counter to determine new section header (fencepost)
		$newsection = 0; 
		foreach ($threads as $thread) { 
			$status = $thread['ThreadStatus'];
			$due = $thread['DueDate'];
			$done = $status == "Complete" || $status =="Closed" || $status == "Rejected" || $status =="Late" || $status == "Failed";
			
			//HEADER: From 'To Review' to  'Currently Lending'
			if ($newsection == 0 && $status=="Current") {
				$newsection++;?>
				</ul> <br>
				<h1>Current Lending</h1><br>
				<ul>
			<?php
			//HEADER: From 'Currently Lending' to 'Past Lending'
			} else if ($newsection <= 1 && $done) {
				$newsection++;?>
				</ul> <br>
				<div data-role="collapsible">
					<h1>Past Lending</h1><br>
					<ul>
			<?php }
			?>
			<li class="<?= $status ?>">
				<div style="float:left">
					<a href="more/accounts/profile/<?= $thread['BorrowerID']?>">
						<img src="<?= $thread['profilePic'] ?>" style="float: left; width: 50px; height: 50px;">
					</a>
				</div>
				
			<!--PLACEHOLDER: Link to thread on click-->
				<a href="http://www.google.com#q=thread">
					<div>
						<!--General-->
						<strong><?= $thread['firstName']." ".$thread['lastName'] ?></strong> - <strong><?= $thread['Name'] ?></strong>  <br> 
						<!--Reputation-->
						Borrowed <?= $thread['Borrowed'] ?> Lent <?= $thread['Lent'] ?> <br>
						<?= $status ?>
						<?= $due ?>
						<!--Latest Message-->
					</div>
				</a>	
				
			<!--BUTTONS-->
				<div style = "clear:left;">
					<?php
						if($status=="Open"|| $status=="Current" ) {
					?>
					<button type="button" name="current">Take Back</item> 
					<?php  } else if ($status=="Requested" ) { 
					?>

					<button type="button" name="requested" value="approved">Approve</button>
					<button type="button" name="requested" value="rejected">Reject</button>
					<?php } else {
					} ?>
				</div>
			</li>
		<?php }
		if ($newsection > 1) {	?>
			</div>
		<?php }
        break;
}?>
