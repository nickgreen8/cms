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

	$h = new Heading(1, null, 'Hello World!');
	$p = new Paragraph(null, 'This is a test');
	$p->setId('test p');

	echo $h->toHtml();
	echo $p->toHtml();