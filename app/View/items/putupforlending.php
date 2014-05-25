<?php

?>
<div id="main">
    <!--
	<h3>Item Name</h3>
	<input id="input_item_name" type="text" size="25" value="Item name">
	
	<h3>Category</h3>
	<select>
		<option value="dvdbluray">DVD and Blu-Ray</option>
		<option value="videogames">Video Games</option>
		<option value="books">Books</option>
		<option value="electronics">Electronics</option>
		<option value="clothing">Clothing</option>
	</select>
	
	<h3>Value</h3>
	<input id="input_item_value" type="text" size="25" value="Value">
	
	<h3>Description</h3>
	<textarea id="input_item_description">Description</textarea>
	
	<h3>Availability</h3>
	<select>
		<option value="availability">Availability</option>
	</select>
	
	<h3>Click here to upload a photo</h3>
	<img>
	<button type="button" id="item_submit">Submit</button>
    -->
    <?php if($inventory){ ?>
    <h3>You have successfully put this item up for lending</h3>
    <a href="/">Click here to go home</a>
    <br />
    <a href="/more/accounts/profile/<?= $_SESSION["userid"] ?>">Click here to view your inventory</a>
    <?php }else{ ?>
    <h3>There has been an error, please make sure you've selected an item</h3>
    <?php } ?>
</div>