<?php
require_once __DIR__ . '/vendor/autoload.php';

use N8G\Grass\Container;
use N8G\Utils\Log;

//Initilise processes
$container = new Container();
$container->run();

//Specify new page is opening
$container->get('log')->custom(sprintf(
	'%s%s===========================%sNew Page Loaded%s===========================%s%s%s%s%s',
	PHP_EOL,
	PHP_EOL,
	PHP_EOL,
	PHP_EOL,
	PHP_EOL,
	date('l jS F Y'),
	PHP_EOL,
	date('H:ia'),
	PHP_EOL)
);


//Define constants
//Root of the application
define('ROOT_DIR', __DIR__ . '/');

//Key directories within the application
define('ASSETS_DIR', ROOT_DIR . 'public/');
define('CACHE_DIR', ROOT_DIR . 'cache/');

define('VIEWS_DIR', 'views/');
define('THEMES_DIR', 'themes/');

//Define any application options
define('DEBUG', true);

try {
	//Inilise Page
	$page = $container->get('output')->init(isset($_REQUEST['id']) && $_REQUEST['id'] !== '' ? $_REQUEST['id'] : 1);
	echo $page->render();
} catch (\Exception $e) {
	echo sprintf('<p><strong>Message:</strong> %s</p><p><strong>File:</strong> %s</p><p><strong>Line No:</strong> #%s</p><p><strong>Trace String:</strong> %s</p>', htmlspecialchars($e->getMessage()), htmlspecialchars($e->getFile()), htmlspecialchars($e->getLine()), htmlspecialchars($e->getTraceAsString()));
}

//Close down processes
$container->tearDown();