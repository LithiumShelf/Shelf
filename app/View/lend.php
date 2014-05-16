<?php

?>

<!--NEED: Item entry view-->
<!--Container-->
<div id="lend_container">
	<section id="add_item">
		<h1>Add an item</h1>
		<button type="button" name="add" onClick="window.location='/lend/items/lendingform'">+</button>
	</section>
	
	<section id="to_review">
		<h1>To Review</h1>
		<!--Load unconfirmed/pending request threads-->
		<ul>
			<?php 
				insertView('threads', 'viewallthreads');	
			?>
		</ul>
	</section>
	
	<!--	
	
	<section id="current_lending">
		<h1>Currently Lending</h1>
	</section>
	
	<section id="past_lending">
		<h1>Past Lending</h1>
	</section> 
	
	-->
	
</div>




<script>
	var id = $(this).parent().parent().parent('li').attr('id');
    $('button:not(button:first)').click(function(){
    	var id = $(this).parent().parent().parent('li').attr('id');
    	var $this = $(this);
        $.post("/ajax/threads/changestatus",{action:$(this).val(), id:id, page:"<?= $page ?>"}, function(data, status){
        	console.log(data);
        		$this.html(data);
        });
    }); 
    //"/ajax/threads/viewthread"
</script>

