<?php
  $BASE_URL = dirname($_SERVER['SCRIPT_NAME']);
  $cur_page = isset($_REQUEST['page']) ?
                $_REQUEST['page'] : 'home';
 
  /** Configuration Variables **/
  define ('DEVELOPMENT_ENVIRONMENT',true);
   
  define('DB_NAME', 'loztwodc_shelf');
  define('DB_USER', 'loztwodc_shelf');
  define('DB_PASSWORD', 'Lithium_INFO');
  define('DB_HOST', 'localhost');
  
  define('PAGINATE_LIMIT', '5');
  
  function exception_handler($exception) {
  echo "Uncaught exception: " , $exception->getMessage(), "\n";
  }

  set_exception_handler('exception_handler');
?>