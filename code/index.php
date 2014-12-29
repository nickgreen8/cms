<?php
namespace Website;

use Components\HTML\Heading,
	Components\HTML\Paragraph,
	Utils\Log;

error_reporting(E_ALL);
date_default_timezone_set('Europe/London');

require_once __DIR__ . '/library/autoload.php';
$loader = Autoload::getInstance();
Log::init(__DIR__ . '/library/Logs/');

	$h = new Heading(1, 'Hello World!');
	$p = new Paragraph('This is a test');
	$p->setId('test p');

	echo $h->toHtml();
	echo $p->toHtml();