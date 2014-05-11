<?php

?>
<div id="cat_container">
	<h1>Category</h1>
        <form action="searchresults" method="get">
        <!--
	<button type="button" name="category" value="dvdbluray">DVD and Blu-Ray</button>
	<button type="button" name="category" value="videogames">Video Games</button>
	<button type="button" name="category" value="books">Books</button>
	<button type="button" name="category" value="electronics">Electronics</button>
	<button type="button" name="category" value="clothing">Clothing</button>
        -->
        <?php
        foreach($cats as $cat){
        ?>
        <button type="submit" name="category" value="<?=$cat["id"]?>"><?=$cat["Category"]?></button>
        <?php
        }
        ?>
        </form>
</div>