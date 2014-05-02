<?php
// Match your item with item from Amazon DB and then put it up for lending
//

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
function ItemSearch($keywords){

//Set the values for some of the parameters
$Operation = "ItemSearch";
$Version = "2011-08-01";
$ResponseGroup = "Images,ItemAttributes";
//User interface provides values
//for $SearchIndex and $Keywords

//Define the request

$requestheader= "GET\nwebservices.amazon.com\n/onca/xml\n";

$request=
   
   
     "AWSAccessKeyId=" . Access_Key_ID
   . "&AssociateTag=" . rawurlencode(Associate_tag)
   . "&Condition=" . rawurlencode("All")
   . "&Keywords=" . rawurlencode($keywords)
   . "&Operation=" . rawurlencode($Operation)
   . "&ResponseGroup=" . rawurlencode($ResponseGroup)
   . "&SearchIndex=" . rawurlencode("All")
   . "&Service=AWSECommerceService"
   . "&Timestamp=" . rawurlencode(date('Y-m-d\TH:i:s.000\Z'))
   . "&Version=" . $Version;
   
$request .= "&Signature=" . rawurlencode(base64_encode(hash_hmac('SHA256', $requestheader . $request, [Replace with AMAZON API SECRET KEY], true)));

//Catch the response in the $response object
$response = file_get_contents('http://webservices.amazon.com/onca/xml?' . $request);
$parsed_xml = simplexml_load_string($response);
printSearchResults($parsed_xml);
}
?>

<?php
function printSearchResults($parsed_xml){
    $numOfItems = $parsed_xml->Items->TotalResults;
    if($numOfItems){
        foreach($parsed_xml->Items->Item as $current){
?>
        <h2> <?= $current->ItemAttributes->Title ?> </h2>
        <a href="<?= $current->DetailPageURL ?>"></a>
        <img src="<?= $current->LargeImage->URL ?>" height="<?= $parsed_xml->Items->Item->LargeImage->Height?>" width="<?= $parsed_xml->Items->Item->LargeImage->Width ?>">
        </a>
        <ul>
            <?php if(isset($current->ASIN)){ ?>
                <li>Amazon Serial Identification Number a.k.a ASIN:<?= $current->ASIN ?></li>
            <?php } ?>
            <?php if(isset($current->DetailPageURL)){ ?>
                <li><a href=<?= $current->DetailPageURL ?>>Product Detail Page on Amazon</a></li>
            <?php } ?>
            <?php if(isset($current->ItemAttributes->ProductGroup)){ ?>
                <li>Product Group:<?= $current->ItemAttributes->ProductGroup ?></li>
            <?php } ?>
            <?php if(isset($current->ItemAttributes->Artist)){ ?>
                <li>Artist:<?= $current->ItemAttributes->Artist ?></li>
            <?php } ?>
            <?php if(isset($current->ItemAttributes->Manufacturer)){ ?>
                <li>Manufacturer:<?= $current->ItemAttributes->Manufacturer ?></li>
            <?php } ?>
        </ul>
<?php
        }
    }
}
if (isset($keywords)){
    ItemSearch($keywords);
} else {
?>
    <form name="itemSearch" action="" method="get">
        What do you wish to lend?: <input type="search" name="keywords">
        <input type="submit" value="Submit">
    </form>
<?php
}
//Run and Print (perform the item search)

?>