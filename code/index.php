<?php
require_once __DIR__ . '/vendor/autoload.php';

use Twig_\Twig\Loader\Filesystem,
	Twig_\Twig\Environment,
	N8G\Grass\Display\Page,
	N8G\Grass\Bootstrap;

//Initilise processes
$bootstrap = new Bootstrap();
$bootstrap->run();

//var_dump($_REQUEST);

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

//Inilise Page
$page = Page::init(isset($_REQUEST['id']) ? $_REQUEST['id'] : 1);
echo $page->render();

//Close down processes
$bootstrap->tearDown();