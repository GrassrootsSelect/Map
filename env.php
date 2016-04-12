<?php
$base = realpath(__DIR__).DIRECTORY_SEPARATOR;
define('BASE_PATH', $base);

define('DEV', true); // development -- you'll get Whoops exceptions

define('GOOGLE_MAPS_API_KEY','AIzaSyDQQEuzyzZUuhUNcWiGPCs2fxHxlvLopXI');
define('SUNLIGHT_FOUNDATION_API_KEY','c1d7c83ceb694aeebe1341403ced3942');
define('BASE_URL', 'http://grassrootsselect.org/');

define('RESOURCE_PATH', BASE_PATH.'resources'.DIRECTORY_SEPARATOR);
define('DATA_PATH', RESOURCE_PATH.'data'.DIRECTORY_SEPARATOR);
define('TEMPLATE_PATH', RESOURCE_PATH.'templates'.DIRECTORY_SEPARATOR);

?>