<?php
require_once __DIR__ . '/vendor/autoload.php';

use N8G\Grass\Bootstrap;
use N8G\Grass\Container;
use N8G\Grass\Application;

//Initilise processes
$container   = new Container;
$bootstrap   = new Bootstrap($container);
$application = new Application($container);

//Get the config
$config = json_decode(file_get_contents('./config/config.json'));

//Get the id of the page requested
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 1;

try {
	//Initilise application
	$bootstrap->run();

	//Populate the container
	$container->populate($config);

	//Build the page
	$application->build($id);
	echo $application->render();
} catch (\Exception $e) {
	echo $application->renderError($e);
}

//Close down processes
$bootstrap->tearDown();
