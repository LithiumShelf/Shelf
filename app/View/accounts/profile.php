<?php

	/*Page template for both user's profile and other people's profile.
	*/

?>
<div id="profile_container">
	<section id="info">
		<h1>Profile Name</h1>
		<img src="" alt="Profile Pic">
		
		<p>
			Level <br>
			% Reputation<br>
			Lent  times | Borrowed times
		</p>
	</section>

	<section id="actions">
		<button class="lend" type="button" name="offer">Offer an item</button>
		<button type="button" name="request">Request as friend</button>
		<button type="button" name="message">Send a message</button>
	</section>

	
	<section id="inventory">
		<h1>Inventory</h1>
		<?php?>
	</section>
	
	<section id="reviews">
		<h1>Reviews</h1>
		<?php?>
	</section>
</div>
