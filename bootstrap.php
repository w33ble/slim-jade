<?php

if (__DIR__ == '__DIR__') {
	die('you need php 5.3 get this working…'); 
}

define('ROOT', realpath(__DIR__).'/');

//include libraries
require_once ROOT . 'vendor/Slim/Slim/Slim.php';

\Slim\Slim::registerAutoloader(); /* Need to run the Autoloader before JadeView can load */

#require_once ROOT . 'lib/JadeHandler.class.php';
require_once ROOT . 'lib/JadeView.php';

//set up the Slim environment
define('SLIM_MODE', 'development');
JadeView::$jadeDirectory = ROOT . 'vendor/jade.php/';
JadeView::$jadeTemplateDirectory = ROOT . 'views/';

$app = new \Slim\Slim(array(
    'mode' => SLIM_MODE,
    'log.path' => ROOT . 'logs',
    'view' => 'JadeView',
    #'templates.path' => 'views',
));

$app->configureMode('production', function() use ($app) {
    $app->config(array(
        'log.enable' => true,
        'log.level' => 4,
        'debug' => false,
    ));

});

$app->configureMode('development', function() use ($app) {
    $app->config(array(
        'log.enable' => false,
        'debug' => true,
    ));
});

?>
