<?php
	global $url;
	$queryString = $url;
	$action = 'viewallthreads';
	$dispatch = new $controller('thread','threads',$action);
	if ((int)method_exists($controller, $action)) {
		call_user_func_array(array($dispatch,$action),$queryString);
		//echo "potential success";
	} else {
		/* Error Generation Code Here */
		echo "the controller method does not exist";
	}
?>
<!--NEED: Search, Borrow History view-->
<!--Container-->
<div id="borrow_container">
	<section id="find_item">
		<h1>Find an item</h1>
		<input id="search_item" type="text" size="25" value="Search by item" >
		<button type="button" name="browse_cat" formaction="/items/findbycategory">Browse by Category</button>
		<button type="button" name="browse_friend" formaction="/items/findbyuser">Browse by Friend</button>

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