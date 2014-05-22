<div id="search_results">
	<section id="filters">
		<!--<input id="search_item" type="text" size="25" formaction="/items/searchresults" >-->
		<form>
		<input type="text" data-type="search" id="filterable-input">
		</form>
		<form action="" method="get">
		<!--Friend-->
		<select name="userid" id="filter_friend">
			<option value="" <?php if(!$friends){ echo 'selected="selected"'; }?>>--Friends--</option>
			<?php
			foreach($friends as $friend){
			?>
			<option value="<?= $friend["id"] ?>" <?php if(isset($_GET["userid"]) && $_GET["userid"] == $friend["id"]){ echo 'selected="selected"'; }?>><?= $friend["Username"] ?></option>
			<?php } ?>
			<!--Foreach (SQLarray) as $friend (join with account to get Firstlast-->
			<!--<option value="...">FirstName Lastname</option>-->
			<option value="">--Locals--</option>
			<?php
			foreach($localusers as $localuser){
			?>
			<option value="<?= $localuser["id"] ?>" <?php if(isset($_GET["userid"]) && $_GET["userid"] == $localuser["id"]){ echo 'selected="selected"'; }?>><?= $localuser["Username"] ?></option>
			<?php } ?>
		</select>	
		
		<!--Category-->
		<select name="category" id="filter_category">
			<option value="" <?php if(!$cats){ echo 'selected="selected"'; }?>>--Pick a Group--</option>
			<?php
			foreach($cats as $cat){
			?>
			<option value="<?= $cat["id"] ?>" <?php if(isset($_GET["category"]) && $_GET["category"] == $cat["id"]){ echo 'selected="selected"'; }?>><?= $cat["Category"] ?></option>
			<?php } ?>
		</select>
		
		<!--Price-->
		<select name="pricerange" id="filter_price">
			<option value="">--Value--</option>
			<option value="0-20">&lt $20</option>
			<option value="20-40">$20-40</option>
			<option value="40-60">$40-60</option>
			<option value="60-80">$60-80</option>
			<option value="80-9999">$80+</option>
		</select>
			<button type="submit">Filter</button>
		</form>
	</section>
	<!-- http://api.jquerymobile.com/filterable/ -->
	<div data-role="controlgroup" data-filter="true" data-input="#filterable-input">
		<?php
		//print_r($items);
		foreach($items as $item){
		?>
		<a href="itempage/<?= $item["id"] ?>" >
			<div class="item">
				<h3><?= $item["Name"] ?></h3>
			</div>
		</a>
		<?php } ?>
	</div>
</div>