<?php
    class Model extends PDOhandler {
            protected $_model;
    
            function __construct() {
    
                    $this->connect();
                    $this->_limit = PAGINATE_LIMIT;
                    //find a datatable that matches the className
                    $this->_model = get_class($this); 
                    $this->_table = $this->_model;
            }
    
            function __destruct() {
            }
    }
?>