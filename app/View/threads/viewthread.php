<?php
    //In view, If fromBorrower = 1, print Borrower Name, If 0, print your User's name
    
    foreach($messages as $message){
        if($message['fromBorrower'] == 1){
            $from = $message["BorrowerName"];
            $to = $message["LenderName"];
        }else{
            $from = $message["LenderName"];
            $to = $message["BorrowerName"];
        }
?>
        <div class="message">
			<hr>
            <p><b>From: <?= $from ?></b> <br>
                <b>To: <?= $to ?></b></p>
            <h4 class="messagesubject"><?= $message["MessageSubject"] ?></h4>
            <p class="messagebody"><?= $message["Body"] ?></p>
           <!-- <?php if($message["hasRead"] == 1){ ?>
            <span class="ui-btn ui-icon-eye ui-btn-icon-left"></span>
            <?php } ?>-->
            <p><i>Sent on <?= $message["Messagetimestamp"] ?></i></p>

        </div>
<?php        
    }
?>