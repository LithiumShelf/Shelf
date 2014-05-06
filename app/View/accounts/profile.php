<?php

	/*Page template for both user's profile and other people's profile.
	*/

?>
<div id="profile_container">
	<section id="info">
		<h1><?=?></h1>
		<img src=<?=?> alt=<?=?>>
		
		<p>
			Level <?=?><br>
			<?=?>% Reputation<br>
			Lent <?=?> times | Borrowed <?=?> times
		</p>
	</section>
	<?php
        //If you're viewing someone else's profile
        if($id != $_SESSION['userid']){
        ?>
	<section id="actions">
		<button class="lend" type="button" name="offer">Offer an item</button>
		<button type="button" name="request">Request as friend</button>
		<button type="button" name="message">Send a message</button>
	</section>
	<?php
        }
        ?>
	
	<section id="inventory">
		<h1>Inventory</h1>
		<?php?>
	</section>
	
	<section id="reviews">
		<h1>Reviews</h1>
		<?php?>
	</section>
</div>
