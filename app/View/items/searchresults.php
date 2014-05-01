<?php

?>

<div id="search_results">
	<section id="filters">
		<input id="search_item" type="text" size="25" formaction="/items/searchresults" >
		
		<!--Friend-->
		<select id="filter_friend">
			<option value="" selected="selected">Friend</option> 
			<!--Foreach (SQLarray) as $friend (join with account to get Firstlast-->
			<!--<option value="...">FirstName Lastname</option>-->
		</select>	
		
		<!--Category-->
		<select id="filter_category">
			<option value="">Category</option>
			<option value="dvdbluray">DVD and Blu-Ray</option>
			<option value="videogames">Video Games</option>
			<option value="books">Books</option>
			<option value="electronics">Electronics</option>
			<option value="clothing">Clothing</option>
		</select>
		
		<!--Price-->
		<select id="filter_price">
			<option value="">Value</option>
			<option value="20minus">&lt $20</option>
			<option value="20to40">$20-40</option>
			<option value="40to60">$40-60</option>
			<option value="60to80">$60-80</option>
			<option value="80plus">$80+</option>
		</select>
	</section>
</div>