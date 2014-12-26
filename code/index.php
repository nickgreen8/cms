<?php
namespace Website;

use Components\HTML\Paragraph;

require_once __DIR__ . '/autoload.php';
$loader = Autoload::getInstance();

error_reporting(E_ALL);

	$p = new Paragraph('test p', 'Hello World!');
	echo $p->toHtml();
?>