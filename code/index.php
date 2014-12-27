<?php
namespace Website;

use Components\HTML\Paragraph,
	Utils\Log;

error_reporting(E_ALL);
date_default_timezone_set('Europe/London');

require_once __DIR__ . '/autoload.php';
$loader = Autoload::getInstance();
Log::init(__DIR__ . '/library/Logs/');

	$p = new Paragraph(null, 'Hello World!');
	$p->setId('test p');

	echo $p->toHtml();
?>