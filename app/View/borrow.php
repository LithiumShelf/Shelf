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

<script>
$('ul > li').not('span').click(function(event){
		var target = $( event.target );
		if (event.target != this) {
			return;
		}
		var id = $(this).attr('id');
		var $this = $(this);
		$subject = $('<input>', {type:"text", name:"subject"});
		$body = $('<input>', {type:"text", name:"body"});
		$send = $('<button>', {text:"send",'data-inline':"true"});
		$options = $('<span>').addClass('options');
		
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
			if (event.target != this ) {
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
    $('button:not(button:first)').click(function(event){
	//event.stopPropagation();
    	var id = $(this).parent().parent().parent('li').attr('id');
    	var $this = $(this);
        var hashCode = $(this).parent().siblings('div.ui-block-a').children().children().val();
        $.post("/ajax/threads/changestatus",{action:$(this).val(), id:id, page:"<?= $page ?>", hashCode: hashCode}, function(data, status){
        	console.log(data);
        		$this.html(data);
        });
    }); 
    //"/ajax/threads/viewthread"
</script>


