<?php
//enable error reporting, please disable for production
ini_set('display_errors',1); 
 //error_reporting(E_ALL); //error reporting on


error_reporting(0); //error reporting off

require_once "config.php";

// This will allow the browser to cache the pages of the app.
header('Cache-Control: max-age=2400, public');
header('Pragma: cache');
header("Last-Modified: ".gmdate("D, d M Y H:i:s",time())." GMT");
header("Expires: ".gmdate("D, d M Y H:i:s",time()+2400)." GMT");

/** Check for Magic Quotes and remove them **/
function stripSlashesDeep($value) {
	$value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
	return $value;
}

function removeMagicQuotes() {
if ( get_magic_quotes_gpc() ) {
	$_GET    = stripSlashesDeep($_GET   );
	$_POST   = stripSlashesDeep($_POST  );
	$_COOKIE = stripSlashesDeep($_COOKIE);
}
}

/** Main Call Function **/
function callHook() {
    global $url;
    if(isset($url) && $url != ""){
	$urlArray = array();
	    $urlArray = explode("/",$url);
	    
	    //must have page designated as the first thing before controller and action
	    $controller = $urlArray[0];
	    array_shift($urlArray);
	    $action = $urlArray[0];
	    array_shift($urlArray);
	    $queryString = $urlArray;
	 
	    $controllerName = $controller;
	    $controller = $controller;
	    $model = ucfirst(rtrim($controller, 's'));
	    $controller .= 'Controller';
	    $dispatch = new $controller($model,$controllerName,$action);
	 
	    if ((int)method_exists($controller, $action)) {
		call_user_func_array(array($dispatch,$action),$queryString);
		//echo "potential success";
	    } else {
		/* Error Generation Code Here */
		echo "the controller method does not exist";
	    }
    }
    
}

/** Autoload any classes that are required **/

function my_autoloader($className) {
    if (file_exists(ROOT . DS . 'lib' . DS . strtolower($className) . '.php')) {
        require_once(ROOT . DS . 'lib' . DS . strtolower($className) . '.php');
    } else if (file_exists(ROOT . DS . 'app' . DS . 'Controller' . DS . strtolower($className) . '.php')) {
        require_once(ROOT . DS . 'app' . DS . 'Controller' . DS . strtolower($className) . '.php');
    } else if (file_exists(ROOT . DS . 'app' . DS . 'Model' . DS . strtolower($className) . '.php')) {
        require_once(ROOT . DS . 'app' . DS . 'Model' . DS . strtolower($className) . '.php');
    } else {
        /* Error Generation Code Here */
        echo "the method or class could not be autoloaded";
    }
}

spl_autoload_register('my_autoloader');

removeMagicQuotes();

function insertView($controller, $action, $queryString = null){
	//global $url;
	    $controllerName = $controller;
	    $controller = $controller;
	    $model = ucfirst(rtrim($controller, 's'));
	    $controller .= 'Controller';
	    $dispatch = new $controller($model,$controllerName,$action);
	if ((int)method_exists($controller, $action)) {
		call_user_func_array(array($dispatch,$action),$queryString);
		//echo "potential success";
	} else {
		/* Error Generation Code Here */
		echo "the controller method does not exist";
	}

}

function findThumbnailPath($filename){
	$pic = $filename;
        $ext = array_pop(explode('.', $pic));
        $thumb = $pic . '_thumb' . $ext;
	return $thumb;
}
?>