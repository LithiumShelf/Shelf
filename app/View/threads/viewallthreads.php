<?php
//CASE SWITCH FOR FEED/BORROW/LEND
switch ($type) {
    case "feed":
        echo "feed";
        break;
		
    case "borrow":
		// Counter for when to separate Currently Requested from Borrowing, fencepost
		$newsection = 0; 
		foreach ($threads as $thread) { 
			$status = $thread['ThreadStatus'];
			$due = $thread['DueDate'];
			
		// If transition from Currently Requested to Borrowing, increment counter
		// PLACEHOLDER: Need actual status names to be updated in DB
			if($newsection == 0 && $status == "Closed") {  
				$newsection++; ?>
				</ul>
				<h1>Borrowing</h1>
				<ul>
			<? } else { ?>
		
			<li class="<?= $status ?>">
				<div>
			<!--PLACEHOLDER: Pass person's account id and load profile-->
					<a href="/accounts/profile/<?= $thread['LenderID']?>">
						<img src="<?= $thread['profilePic'] ?>" style="float: left; width: 50px; height: 50px;">
					</a>
				</div>
				
			<!--PLACEHOLDER: Link to thread on click-->
				<a href="http://www.google.com#q=thread">
					<div style="float: left;">
						<!--General-->
						<strong><?= $thread['Name'] ?>Item</strong> from <strong><?= $thread['firstName']." ".$thread['lastName'] ?></strong> <br> 
						<!--Reputation-->
						Borrowed <?= $thread['Borrowed'] ?> Lent <?= $thread['Lent'] ?> <br>
						<!--Status-->
						<?= $status ?>
						<br>
						<?php 
							if(isset($due)) {
								print "Due Date: ". $due;

							} else {
								print "Pending";
							}
						?>
					</div>
				</a>
			<!--Next steps
			PLACEHOLDER: Until we add the additional status states into our DB
			-->
				<div style = "clear:left;">
					<?php
						if(isset($due) && $status=="Open") {
					?>
					<button type="button" name="return">Return</item> 
					<?php  } else if (!isset($due) && $status=="Open" ) { 
					?>

					<button type="button" name="" formaction="">Receive</button>
					<button type="button" name="" formaction="">Cancel</button>
					<?php } else {} ?>
				</div>
			</li>
		<?php 
			}}
		break;
		
    case "lend":
		$newsection = 0; 
		foreach ($threads as $thread) { 
			$status = $thread['ThreadStatus'];
			$due = $thread['DueDate'];
			if ($newsection == 0 && $status == "Closed") {
				$newsection++;?>
				</ul>
				<h1>Past Lending</h1>
				<ul>
			<?php } else {
			?>
			<li class="<?= $status ?>">
				<div>
			<!--PLACEHOLDER: Pass person's account id and load profile-->
					<a href="/accounts/profile/<?= $thread['LenderID']?>">
						<img src="<?= $thread['profilePic'] ?>" style="float: left; width: 50px; height: 50px;">
					</a>
				</div>
				
			<!--PLACEHOLDER: Link to thread on click-->
				<a href="http://www.google.com#q=thread">
					<div style="float: left;">
						<!--General-->
						<strong><?= $thread['firstName']." ".$thread['lastName'] ?></strong> - <strong><?= $thread['Name'] ?>Item</strong>  <br> 
						<!--Reputation-->
						Borrowed <?= $thread['Borrowed'] ?> Lent <?= $thread['Lent'] ?> <br>
						<br>
						<!--Latest Message-->
						
					</div>
				</a>	
		
		<?php } }
        echo "lend";
        break;
}?>
