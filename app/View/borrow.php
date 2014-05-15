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
		<ul>
			<?php 
				insertView('threads', 'viewallthreads');
			?>
		</ul>
	</section>
	
</div>