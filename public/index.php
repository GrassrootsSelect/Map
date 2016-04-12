<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('../env.php');
require_once('../bootstrap.php');

$errorHandler = function ($errno, $errstr, $errfile, $errline) {
    require BASE_PATH.'resources/templates/error.tpl.php';
    var_dump(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));
    die();
};
$exceptionHandler = function ($exception) use ($errorHandler) {
    $errorHandler(E_ERROR, $exception->getMessage(), $exception->getFile(), $exception->getLine());
};

set_error_handler($errorHandler);
register_shutdown_function(function () use ($errorHandler) {
    $last_error = error_get_last();
    if ($last_error['type'] === E_ERROR) {
        $errorHandler(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
    }
});

$app = new Silex\Application();
$app['debug'] = DEV;
$app->error($exceptionHandler);
include(BASE_PATH.'routes.php');
$app->run();

?>