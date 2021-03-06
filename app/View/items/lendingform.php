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
            Upload your own image:
            <style scoped>
                #imguploadWrapper {
                    height: 90px;
                    background: url('http://www.clker.com/cliparts/8/n/1/t/R/c/camera-icon-th.png') 0 0 no-repeat;
                    border:none;
                    overflow:hidden;
                }
                #imgupload, .ui-input-text.ui-body-inherit.ui-corner-all.ui-shadow-inset{
                    margin-left:-145px;
                    opacity:0;
                    -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
                    filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=0);
                    /*display:none;*/
                }
                #camera{
                    display:inline-block;
                    width:1em;
                }
                label{
                    clear:right;
                }
            </style>
            <div id=imguploadWrapper>
                <input type="file" id="imgupload" name="img" accept="image/*" capture="camera">
            </div>
            <fieldset data-role="controlgroup">
               <div data-role="footer" data-position="fixed">
                  <button type="submit" value="Submit">Submit</button>
               </div>
                
                Choose your product
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
        <div class="ui-grid-a my-breakpoint">
         <div class="ui-block-a">
         <a href="<?= $current->DetailPageURL ?>">
         <img src="<?= $current->LargeImage->URL ?>">
         </a>
         </div>
          <div class="ui-block-b">
         <ul>
             <?php if(isset($current->ItemAttributes->ProductGroup)){ ?>
                 <li>Product Group: <?= $current->ItemAttributes->ProductGroup ?></li>
             <?php } ?>
             <?php if(isset($current->ItemAttributes->Artist)){ ?>
                 <li>Artist: <?= $current->ItemAttributes->Artist ?></li>
             <?php } ?>
             <?php if(isset($current->ItemAttributes->Manufacturer)){ ?>
                 <li>Manufacturer: <?= $current->ItemAttributes->Manufacturer ?></li>
             <?php } ?>
         </ul>
          </div>
        </div>
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