<?php

//bootstrap the application
require_once 'bootstrap.php';

//set up your routes
$app->get('/', function() use ($app) {
	$app->render('welcome', 
		array('files' => array('bootstrap.php : All the app configuration happens here', 'index.php : All your routes live here'))
	);
});

//let Slim work its magic
$app->run();

?>
