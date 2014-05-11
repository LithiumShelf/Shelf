<?php
//get info for a particular item off of amazon given its Amazon Serial Item Number

//Enter your IDs
define("Access_Key_ID", "AKIAIIDBSE7VIVWZP7VA");
define("Associate_tag", "ekurea-20");

//set default time zone
date_default_timezone_set('UTC');

//check your search results
function checkSearchResults($parsed_xml){
    //Verify a successful request
    foreach($parsed_xml->OperationRequest->Errors->Error as $error){
       echo "Error code: " . $error->Code . "\r\n";
       echo $error->Message . "\r\n";
       echo "\r\n";
    }
}
//Set up the operation in the request
function ItemLookup($ASIN, $item){

//Set the values for some of the parameters
$Operation = "ItemLookup";
$Version = "2011-08-01";
$ResponseGroup = "Images,ItemAttributes,Similarities";
//User interface provides values
//for $SearchIndex and $Keywords

//Define the request

$requestheader= "GET\nwebservices.amazon.com\n/onca/xml\n";

$request=
    "AWSAccessKeyId=" . Access_Key_ID
   . "&AssociateTag=" . rawurlencode(Associate_tag)
   . "&ItemId=" . rawurlencode($ASIN)
   . "&Operation=" . rawurlencode($Operation)
   . "&ResponseGroup=" . rawurlencode($ResponseGroup)
   . "&Service=AWSECommerceService"
   . "&Timestamp=" . rawurlencode(date('Y-m-d\TH:i:s.000\Z'))
   . "&Version=" . $Version;
   
$request .= "&Signature=" . rawurlencode(base64_encode(hash_hmac('SHA256', $requestheader . $request, AMAZON_SECRET, true)));
//echo $request;
//Catch the response in the $response object
$response = file_get_contents('http://webservices.amazon.com/onca/xml?' . $request);
$parsed_xml = simplexml_load_string($response);
printSearchResults($parsed_xml, $item);
}
?>

<?php
function printSearchResults($parsed_xml, $item){
    if($parsed_xml->Items->Request->Errors){
        //Item cannot be accessed via API.
        echo "Error Code: " . $parsed_xml->Items->Request->Errors->Error->Code;
        echo "Message: " . $parsed_xml->Items->Request->Errors->Error->Message;
    }else{
?>
<h2> <?= $parsed_xml->Items->Item->ItemAttributes->Title ?> </h2>
<a href="<?= $parsed_xml->Items->Item->DetailPageURL ?>"></a>
<?php if(isset($item[0]["ItemPic"])){ ?>
<img src="<?= $BASE_URL ?>/images/item/<?=$item[0]["ItemPic"]?> ?>">
<?php }else{ ?>
<img src="<?= $parsed_xml->Items->Item->LargeImage->URL ?>" height="<?= $parsed_xml->Items->Item->LargeImage->Height?>" width="<?= $parsed_xml->Items->Item->LargeImage->Width ?>">
<?php } ?>
</a>
<ul>
    
    <li>Owner is: <?= $item[0]["Username"] ?></li>
    <li>Click here to request item</li>
    <?php if(isset($parsed_xml->Items->Item->ItemAttributes->ProductGroup)){ ?>
        <li>Product Group:<?= $parsed_xml->Items->Item->ItemAttributes->ProductGroup ?></li>
    <?php } ?>
    <?php if(isset($parsed_xml->Items->Item->ItemAttributes->Artist)){ ?>
        <li>Artist:<?= $parsed_xml->Items->Item->ItemAttributes->Artist ?></li>
    <?php } ?>
    <?php if(isset($parsed_xml->Items->Item->ItemAttributes->Manufacturer)){ ?>
        <li>Manufacturer:<?= $parsed_xml->Items->Item->ItemAttributes->Manufacturer ?></li>
    <?php } ?>
    <?php if(isset($parsed_xml->Items->Item->ItemAttributes->ListPrice->FormattedPrice)){ ?>
        <li>List Price:<?= $parsed_xml->Items->Item->ItemAttributes->ListPrice->FormattedPrice ?></li>
    <?php } ?>
</ul>
<?php
    }
}
//Run and Print (perform the item lookup)
ItemLookup($item[0]["ASIN"], $item);
?>