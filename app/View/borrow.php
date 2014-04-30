<?php

?>
<!--NEED: Search, Borrow History view-->
<!--Container-->
<div id="main">
	<section id="find_item">
		<h1>Find an item</h1>
		<input id="search_item" type="text" size="25" value="Search by item">
		<button type="button" name="browse_cat">Browse by Category</button>
		<button type="button" name="browse_friend">Browse by Category</button>
	</section>
	
	<section id="currently_requested">
		<h1>Currently requested</h1>
		<!--Load requests:
				-Profile image + Username + Item name
				-Reputation
				-Status
				
				-Recieve + Cancel Button
				-Hash Code text field
		-->
		<?php?>
	</section>
	
	<section id="borrowing">
		<h1>Borrowing</h1>
		<!--Load items:
				-Profile image + Username + Item name
				-Reputation
				-Due date
				
				-Return button
		-->
		<?php?>
		<button type="button" name="borrow_history">Borrow History</button>
	</section>
	
</div>