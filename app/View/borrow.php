<?php
	//For debugging, assume login
	//echo "HELLO WORLD";
	$_SESSION['userid']=  2;
	global $url;
	$queryString = $url;
	$action = 'viewallthreads';
	$controller= 'threadsController';
	$dispatch = new $controller('Thread','threads',$action);
	if ((int)method_exists($controller, $action)) {
		call_user_func_array(array($dispatch,$action),$queryString);
		echo "potential success";
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
		<input id="search_item" type="text" size="25" formaction="/items/searchresults" value="Search by item" >
		<button type="submit" name="browse_cat" formaction="/items/findbycategory">Browse by Category</button>
		<button type="submit" name="browse_friend" formaction="/items/findbyuser">Browse by Friend</button>
	</section>
	
	<section id="currently_requested">
		<h1>Currently requested</h1>
		<!--Load requests:
				-Profile image + Username + Item name
				-Reputation
				-Status
				
				-Receive + Cancel Button
				-Hash Code text field
		-->
		<ul>
		<?php 
			//NEED: Change query so that JOINs with Account to get First and Last Name
			//NEED: Different ThreadStatus in test data: Pending, Open, Closed
			/*
				SELECT * FROM :thistable 
					RIGHT JOIN Item ON (Item.id = Thread.ItemID) 
					JOIN Account ON (Item.LenderID = Account.id)
				WHERE :actionID= :UserID

			*/
			echo $type;
			print_r($threads);
			foreach ($threads as $thread) {
				$status = $thread["ThreadStatus"];
				if ($status == 'Pending') { 
			?>
				<li class="borrow_active"> 
					<img src="<?= $thread["ItemPic"] ?>" >
				</li>
		<?php 
				}
			} 
		?>
		</ul>
	</section>
	
	<section id="borrowing">
		<h1>Borrowing</h1>
		<!--Load items:
				-Profile image + Username + Item name
				-Reputation
				-Due date
				
				-Return button
		-->
		<?php
			print_r ($threads);
		?>
		
			
		<button type="button" name="borrow_history">Borrow History</button>
	</section>
	
</div>