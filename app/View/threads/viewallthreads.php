<?php
//CASE SWITCH FOR FEED/BORROW/LEND
switch ($type) {
    case "feed":
		foreach ($threads as $thread) { 
			$itemname = $thread['Name'];
			$src = $thread['ItemPic'];
			$person = $thread['bFirst']." ".$thread['bLast'];
		 ?>
		<li>
			<!--Feed Tile: Top-->
			<div style="float: left;"> 
				<a href="more/accounts/profile/<?= $thread['BorrowerID']?>">
					<img src="<?= $BASE_URL ?>/images/profile/<?= $thread['profilePic']?>" alt="<?=$person?>" style="float: left; width: 50px; height: 50px;">
				</a>
			</div>
			
			<p>
				<a href="more/accounts/profile/<?= $thread['BorrowerID']?>">
					<strong><?=$person?></strong> 
				</a>
				is borrowing 
				<a href="feed/items/itempage/<?=$thread['ItemID']?>" >
					<strong><?=$itemname ?></strong>.
				</a>
			</p>
			
			<!--Feed Tile: Image-->
			<a href="feed/items/itempage/<?= $thread['ItemID'] ?>">
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
		$currentstat = "Open"; //Placeholder for real status
		if ($threads) {
			foreach ($threads as $thread) { 
				$status = $thread['ThreadStatus'];
				$due = $thread['DueDate'];
				$done = $status =="Closed" || $status == "rejected" || $status =="late" || $status == "failed";
			//HEADER: If transition from Currently Requested to Borrowing, increment counter
				// To make it so that they still show up even if empty, remove else if...
				if($newsection == 0 && ($status=="current" || $status =="Open")){  
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
					<div data-role="collapsible" data-collapsed="false">
						<h1>Borrow History</h1>
						<ul>
				<?php 
					}
				?>
				
				
				<li class="<?= $status?>" id="<?=$thread['ThreadID']?>">
					<div style="float: left;">
						<a href="more/accounts/profile/<?= $thread['LenderID']?>">
							<img src="<?= $thread['profilePic'] ?>" style="float: left; width: 50px; height: 50px;">
						</a>
					</div>
					
				<!--PLACEHOLDER: Link to thread on click-->
	
						<div >
							<!--General-->
							<a href="borrow/items/itempage/<?= $thread['ItemID']?>">
								<strong><?= $thread['Name'] ?></strong>  
							</a>
							from
							<a href="more/accounts/profile/<?= $thread['LenderID']?>">
								<strong><?= $thread['firstName']." ".$thread['lastName'] ?></strong> 
							</a>
							<br> 
							<!--Reputation-->
							Borrowed <?= $thread['Borrowed'].' ' ?> Lent <?= $thread['Lent'] ?> <br>
							<!--Status-->
							<?= $status ?>

						</div>
				
					
				<!--BUTTONS-->
					<div style = "clear:left;" class="ui-grid-a ui-responsive">
						<?php
							switch($status){
							    case "Open":
							    case "current":
						?>
						<div><button type="button" name="action" value="return">Return</button> </div>
						<?php  break;
							case "requested":
						?>

						<div class="ui-block-a"> 
							<button type="button" name="action" value="current">Receive</button>
						</div>
						<div class="ui-block-b">
							<button type="button" name="action" value="cancelled">Cancel</button>
						</div>
						<?php break;
							case "approved":
						?>
						<div class="ui-block-a">
						    <input type="text" name="hashCode">
						</div>
						<div class="ui-block-b">
						    <button type="button" name="action" value="current" data-mini="true" data-inline="true">Borrow</button>
						    <button type="button" name="action" value="cancelled" data-mini="true" data-inline="true">Cancel</button>
						</div>
						<?php
						} ?>
					</div>
				</li>
			<?php }
			if ($newsection > 1) {
			?>
			</div>
			<?php } 
		}else { 
			print "No recent activity";
		}
		break;
		
    case "lend":
		//Counter to determine new section header (fencepost)
		$newsection = 0; 
		if ($threads) {
			foreach ($threads as $thread) { 
				$status = $thread['ThreadStatus'];
				$due = $thread['DueDate'];
				$done = $status == "complete" || $status =="Closed" || $status == "rejected" || $status =="late" || $status == "failed";
				
				//HEADER: From 'To Review' to  'Currently Lending'
				if ($newsection == 0 && $status=="current") {
					$newsection++;?>
					</ul> 
					<h1>Current Lending</h1>
					<ul>
				<?php
				//HEADER: From 'Currently Lending' to 'Past Lending'
				} else if ($newsection <= 1 && $done) {
					$newsection=2;?>
					</ul> 
					<div data-role="collapsible">
						<h1>Past Lending</h1>
						<ul>
				<?php }
				?>
				<li class="<?= $status ?>" id="<?=$thread['ThreadID']?>">
					<div style="float:left">
						<a href="more/accounts/profile/<?= $thread['BorrowerID']?>">
							<img src="<?= $thread['profilePic'] ?>" style="float: left; width: 50px; height: 50px;">
						</a>
					</div>
					
				<!--PLACEHOLDER: Link to thread on click-->
					<div>
						<!--General-->
						<a href="more/accounts/profile/<?= $thread['BorrowerID']?>">
							<strong><?= $thread['firstName']." ".$thread['lastName'] ?></strong> 
						</a>
						- 
						<a href="lend/items/itempage/<?= $thread['ItemID']?>">
							<strong><?= $thread['Name'] ?></strong>  
						</a>
						<br> 
						<!--Reputation-->
						Borrowed <?= $thread['Borrowed'].' ' ?> Lent <?= $thread['Lent'] ?> <br>
						<?= $status ?>

						<!--Latest Message-->
					</div>
					
					
				<!--BUTTONS-->
					<div style = "clear:left;" class="ui-grid-a ui-responsive">
						<?php
							switch($status){
							    case "Open":
							    case "current":
						?>
						<div class="ui-block-a" style = "clear:none;">
							<button type="button" name="action" value="complete">Complete</button> 
						</div>
						<div class="ui-block-b" style = "clear:none;">
							<button type="button" name="action" value="failed">Fail</button> 
						</div>
						
						<?php  break;
							case "requested";
						?>
						<div class="ui-block-a" style = "clear:none;">
							<button type="button" name="action" value="approved">Approve</button>
						</div>
						
						<div class="ui-block-b" style = "clear:none;">
							<button type="button" name="action" value="rejected">Reject</button>
						</div>
						<?php break;
							case "approved":
						?>
						<div class="ui-block-a" style = "clear:none;">
						    Give the borrower this code:
						</div>
						<div class="ui-block-b" style = "clear:none;">
						    <?= $thread['HashCode'] ?>
						</div>
						<?php
							break;
						} ?>
					</div>
				</li>
			<?php }
			if ($newsection > 1) {	?>
				</div>
			<?php }
		} else { 
			print "No recent activity";
		}
        break;
}?>
