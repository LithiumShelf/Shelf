<?php
	//For debugging, assume login
	$_SESSION['userid']=  2;
?>
<!--NEED: Search, Borrow History view-->
<!--Container-->
<div id="borrow_container">
	<section id="find_item">
		<h1>Find an item</h1>
		<form action="borrow/items/searchresults" method="get">
			<input name="search_item" type="text" size="25" >
			<button type="submit">Search</button>
			<button type="submit" name="browse_cat" formaction="borrow/items/findbycategory"> 
				Browse by Category
			</button>
			<button type="submit" name="browse_friend" formaction="borrow/items/findbyuser">Browse by Friend</button>
		</form>
	</section>
	
	<section id="currently_requested">
		<h1>Currently requested</h1>
		<!--Load requests:
				-Profile image + lender name + Item name
				-Reputation
				-Status of transaction
				
				-Receive + Cancel Button
				-Hash Code text field
		-->
		<ul>
		<?php 
			insertView('threads', 'viewallthreads');
			echo $type;
			print_r($threads);
			foreach ($threads as $thread) {
				$status = $thread["ThreadStatus"];
				if ($status == 'Open') { 
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