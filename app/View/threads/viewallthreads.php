<?php
//CASE SWITCH FOR FEED/BORROW/LEND
//$_SESSION['userid']=  2;
switch ($type) {
    case "feed":
        echo "feed";
        break;
    case "borrow":
		$oldstatus = "Open"; /*Should be for pending status*/
		foreach ($threads as $thread) { 
			$status = $thread['ThreadStatus'];
			$due = $thread['DueDate'];
			
		/*	If status of current status changes & != previous status
		 *	make new section
		 */
			if($status != $oldstatus) {  ?>
			</ul>
			<h1>Borrowing</h1>
			<ul>
		<? } else { ?>
		
			<li class="<?= $status ?>">
				<div>
					<a href="/accounts/profile/<?= $thread['LenderID']?>">
						<img src="<?= $thread['profilePic'] ?>" style="float: left; width: 50px; height: 50px;">
					</a>
				</div>
				
			<!--Link to thread on click (placeholder)-->
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
			<!--Next steps-->
				<div style = "clear:left;">
					<?php
						if(isset($due) && $status=="Open") {
					?>
					<button type="button" name="return">Return</item> 
					<?php  } else { 
					?>

					<button type="button" name="" formaction="">Receive</button>
					<button type="button" name="" formaction="">Cancel</button>
					<?php } ?>
				</div>
			</li>
		<?php 
			}
		
		break;
    case "lend":
        echo "lend";
        break;
}?>