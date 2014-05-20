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
$('li').click(function(event){
		if (event.target != this) {
			return;
		}
		var id = $(this).attr('id');
		var $this = $(this);
		$subject = $('<input>', {type:"text", name:"subject"});
		$body = $('<input>', {type:"text", name:"body"});
		$send = $('<button>', {text:"send",'data-inline':"true"});
		$options = $('<div>').addClass('options');
		
		$options.append("Subject:");
		$options.append($subject);
		$options.append("Body:");
		$options.append($body);
		$options.append($send);
		$this.append($options);
		
		$send.click(function(){
			event.stopPropagation();
			$button = $(this);
			$.post("/ajax/threads/composemessage",{subject: $subject.val(), body:$body.val(), threadID:id, page:"<?= $page ?>"}, function(data){
				$button.after(data);
			})
		})
		$.post("/ajax/threads/viewthread",{action:$this.val(), id:id}, function(data, status){
			$this.append(data);
			//$this.html("Success");
		});
		$this.off('click');
		//console.log(id + " " + $(this).val());
		$this.click(function(){
			if (event.target != this) {
				return;
			}
			var selector = '#' +id + ' .options, ' + '#'+ id +' .message';
			$(selector).toggle();
		});
	});
	//button:not(button:first)
	//ui-grid-b ui-responsive
	//$(this).parent().parent().parent('li').attr('id');
</script>
<script>
    $('button:not(button:first)').click(function(){
	event.stopPropagation();
    	var id = $(this).parent().parent().parent('li').attr('id');
    	var $this = $(this);
        $.post("/ajax/threads/changestatus",{action:$(this).val(), id:id, page:"<?= $page ?>"}, function(data, status){
        	console.log(data);
        		$this.html(data);
        });
    }); 
    //"/ajax/threads/viewthread"
</script>

