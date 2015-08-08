<?php
require_once __DIR__ . '/vendor/autoload.php';

use Twig_\Twig\Loader\Filesystem,
	Twig_\Twig\Environment,
	N8G\Grass\Display\Output,
	N8G\Grass\Bootstrap;

//Initilise processes
$bootstrap = new Bootstrap();
$bootstrap->run();

//Define constants
//Root of the application
define('ROOT_DIR', __DIR__ . '/');

//Key directories within the application
define('ASSETS_DIR', ROOT_DIR . 'assets/');
define('CACHE_DIR', ROOT_DIR . 'cache/');

define('VIEWS_DIR', 'views/');
define('THEMES_DIR', 'themes/');

//Define any application options
define('DEBUG', true);

try {
	//Inilise Page
	$page = Output::init(isset($_REQUEST['id']) ? $_REQUEST['id'] : 1);
	echo $page->render();
} catch (\Exception $e) {
	echo sprintf('<p><strong>Message:</strong> %s</p><p><strong>File:</strong> %s</p><p><strong>Line No:</strong> #%s</p><p><strong>Trace String:</strong> %s</p>', htmlspecialchars($e->getMessage()), htmlspecialchars($e->getFile()), htmlspecialchars($e->getLine()), htmlspecialchars($e->getTraceAsString()));
}

//Close down processes
$bootstrap->tearDown();