<?php

?>
<!--NEED: Item entry view-->
<!--Container-->
<div id="lend_container">
	<section id="add_item">
		<h1>Add an item</h1>
		<button type="button" name="add" onClick="window.location='lend/items/lendingform'">+</button>
	</section>
	
	<section id="to_review">
		<h1>To review</h1>
		<!--Load unconfirmed/pending request threads-->
		<?php ?>
	</section>
	
	<section id="current_lending">
		<h1>Currently Lending</h1>
		<!--Load current threads-->
		<?php ?>
	</section>
	
	<section id="past_lending">
		<h1>Past Lending</h1>
		<!--Load older lending transaction threads-->
		<?php ?>
	</section>
	
</div>