<?php 
	foreach ($inventory as $item) {
		$itemname = $item['Name'];
		if (!$itemname) {
			$itemname = "Unnamed Item";
		}
?> 
<li><button value="<?=$item['ItemID']?>"><?= $itemname ?></button></li>
<?php
	}	
?>
