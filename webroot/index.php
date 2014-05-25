<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));
require_once (ROOT . DS . 'bootstrap.php');

//specify navigation order
$pages = array("feed", "borrow", "lend", "more");

if(isset($_GET['page'])){
    $page = $_GET['page'];
    
}else{
    $page = "feed";
}
session_start();
if($page == "ajax"){
                if(isset($_GET['url'])){
                     $url = $_GET['url'];
                    callHook();
               }else{
                    include(ROOT . DS . 'app' . DS . 'View' . DS . $page . '.php');
               }
}else{


?>
<!DOCTYPE html>
<html>
    <head>
         <title>Shelf | <?= ucwords($page); ?></title>
         <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="<?= $BASE_URL ?>/css/jquery.mobile-1.4.2.css" />
        <!--
        <link rel="stylesheet" type="text/css" href="<?= $BASE_URL ?>/css/jquery.mobile.icons-1.4.2.min.css" />
        <link rel="stylesheet" type="text/css" href="<?= $BASE_URL ?>/css/jquery.mobile.inline-svg-1.4.2.min.css" />
        <link rel="stylesheet" type="text/css" href="<?= $BASE_URL ?>/css/jquery.mobile.inline-png-1.4.2.min.css" />
        <link rel="stylesheet" type="text/css" href="<?= $BASE_URL ?>/css/jquery.mobile.external-png-1.4.2.min.css" />
        <link rel="stylesheet" type="text/css" href="<?= $BASE_URL ?>/css/jquery.mobile.structure-1.4.2.min.css" />
        -->
        <link rel="stylesheet" type="text/css" href="<?= $BASE_URL ?>/css/general.css" />
		
		<link rel="icon" href="favicon.ico" type="image/x-icon" />
		
        <script src="<?= $BASE_URL ?>/js/jquery-2.1.0.min.js"></script>
        <script src="<?= $BASE_URL ?>/js/jquery.mobile-1.4.2.min.js"></script>
    </head>
    <body>
        <style scoped>
            section.ui-page{
                max-width:768px;
                margin:0 auto;
                position:relative;
                left:auto;
            }
        </style>
        <?php include(ROOT . DS . 'app' . DS . 'View' . DS . 'header.php');?>
        <?php
            if(isset($_SESSION["userid"]) || isset($_GET["url"])){
                if(isset($_GET['url'])){
                     $url = $_GET['url'];
                    callHook();
               }else{
                    include(ROOT . DS . 'app' . DS . 'View' . DS . $page . '.php');
               }
            }else{
                echo 'You are not logged in, <a href="/more/accounts/logonform"> Click here to login </a>';
            }        
        ?>
    </body>
</html>

<?php } ?>