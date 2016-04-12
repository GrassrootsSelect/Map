<?php
// composer autoload
require_once BASE_PATH.'vendor/autoload.php';
// gsmap autoload & register
require_once(BASE_PATH.'src'.DIRECTORY_SEPARATOR.'Autoloader.php');
\GRSelect\AutoLoader::register();
\GRSelect\AutoLoader::registerDirectory(BASE_PATH.'src','GRSelect');
?>