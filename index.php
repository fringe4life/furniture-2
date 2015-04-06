<?php

set_include_path('./php-includes' . PATH_SEPARATOR . './functions');


// Includes
require_once 'connect.inc.php';
require_once 'head.inc.html';
require_once 'header.inc.html';

session_start();
require_once 'functions/csrf_token_functions.php';

require_once 'tab-1.php';
require_once 'footer.inc.html';

?>