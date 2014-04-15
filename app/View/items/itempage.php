<?php


//Enter your IDs
define("Access_Key_ID", "AKIAIIDBSE7VIVWZP7VA");
define("Associate_tag", "ekurea-20");

//check your search results
function checkSearchResults($parsed_xml);
    //Verify a successful request
    foreach($parsed_xml->OperationRequest->Errors->Error as $error){
       echo "Error code: " . $error->Code . "\r\n";
       echo $error->Message . "\r\n";
       echo "\r\n";
}

//Set up the operation in the request
function ItemLookup($ASIN){

//Set the values for some of the parameters
$Operation = "ItemLookup";
$Version = "2011-08-01";
$ResponseGroup = "Images,ItemAttributes,Similarities";
//User interface provides values
//for $SearchIndex and $Keywords

//Define the request

$stringtoencode = "
GET
webservices.amazon.com
/onca/xml
"

$request=
   "Service=AWSECommerceService"
   . "&AssociateTag=" . Associate_tag
   . "&AWSAccessKeyId=" . Access_Key_ID
   . "&ItemId=" . $ASIN
    . "&Operation=" . $Operation
   . "&ResponseGroup=" . $ResponseGroup
   . "&Timestamp=" . urlencode(date('Y-m-d\TH:i:s\Z'))
   . "&Version=" . $Version
   
$request .= "&Signature=" hash_hmac(SHA256, $stringtoencode . $request, [Insert Amazon Secret Key Here])

//Catch the response in the $response object
$response = file_get_contents("http://webservices.amazon.com/onca/xml?" . $request);
$parsed_xml = simplexml_load_string($response);
printSearchResults($parsed_xml);
}
?>

<?php
function printSearchResults($parsed_xml){
?>
<h2> <?= $parsed_xml->Items->Item->ItemAttributes->Title ?> </h2>
<a href="<?= $parsed_xml->Items->Item->DetailPageURL ?>"></a>
<img src="<?= $parsed_xml->Items->Item->LargeImage->URL ?>" height="<?= $parsed_xml->Items->Item->LargeImage->Height?>" width="<?= $parsed_xml->Items->Item->LargeImage->Width ?>">
</a>
<ul>
    <?php if(isset($parsed_xml->Items->Item->ItemAttributes->ProductGroup)){ ?>
        <li>Product Group:<?= $parsed_xml->Items->Item->ItemAttributes->ProductGroup ?></li>
    <?php } ?>
    <?php if(isset($parsed_xml->Items->Item->ItemAttributes->Artist)){ ?>
        <li>Artist:<?= $parsed_xml->Items->Item->ItemAttributes->Artist ?></li>
    <?php } ?>
    <?php if(isset($parsed_xml->Items->Item->ItemAttributes->Manufacturer)){ ?>
        <li>Manufacturer:<?= $parsed_xml->Items->Item->ItemAttributes->Manufacturer ?></li>
    <?php } ?>
</ul>
<?php 
}
?>