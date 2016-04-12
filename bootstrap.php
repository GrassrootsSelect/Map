<?php
if (file_exists('env.local.php') == true)
    require_once('env.local.php');
else
    require_once('env.php');
// composer autoload
require_once BASE_PATH.'vendor/autoload.php';
// gsmap autoload & register
require_once(BASE_PATH.'src'.DIRECTORY_SEPARATOR.'Autoloader.php');
\GRSelect\AutoLoader::register();
\GRSelect\AutoLoader::registerDirectory(BASE_PATH.'src','GRSelect');
?>