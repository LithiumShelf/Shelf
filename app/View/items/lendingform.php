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
   
$request .= "&Signature=" . rawurlencode(base64_encode(hash_hmac('SHA256', $requestheader . $request, AMAZON_SECRET, true)));

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
        ?>
            <div class="ui-field-contain">
            <form enctype="multipart/form-data" action="putupforlending" method="post" data-ajax="false">
            Upload your own image: <input type="file" name="img">
             
            <fieldset data-role="controlgroup">
                <button type="submit" value="Submit">Submit</button>
                <legend>Choose your product</legend>
        <?php
        foreach($parsed_xml->Items->Item as $current){
            $values = $current->ASIN . '|' . $current->ItemAttributes->Title . '|' . $current->ItemAttributes->ProductGroup;
            if($current->ItemAttributes->ListPrice->Amount){
                $values .= "|" . $current->ItemAttributes->ListPrice->Amount / 100;
            }else{
                $values .= "|";
            }
            if($current->LargeImage->URL){
                $values .= "|" . $current->LargeImage->URL;
            }
?>
    <input type="radio" name="product" id="<?= $current->ASIN ?>" value="<?= $values ?>">
    <style scoped>
    label{background-color: #CA0065 !important;
    color: #FFFFFF !important;
    margin: 5px;}
    li{margin: 0.4em;
        padding:0.4em;}
    </style>
    <label for="<?= $current->ASIN ?>">
        <h2> <?= $current->ItemAttributes->Title ?> </h2>
        <a href="<?= $current->DetailPageURL ?>"></a>
        <img src="<?= $current->LargeImage->URL ?>"?>">
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
    </label>
<?php
        }
        ?>
        
            </fieldset>
         </form>
        </div>
        <?php
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
    <h6>Powered by Amazon</h6>
<?php
}
//Run and Print (perform the item search)

?>