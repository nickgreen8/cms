<?php
namespace Website;

use Components\HTML\Header,
	Components\HTML\Footer,
	Components\HTML\Section,
	Components\HTML\Article,
	Components\HTML\Div,
	Components\HTML\Heading,
	Components\HTML\Paragraph,
	Utils\Log;

error_reporting(E_ALL);
date_default_timezone_set('Europe/London');

require_once __DIR__ . '/library/autoload.php';
$loader = Autoload::getInstance();
Log::init(__DIR__ . '/library/Logs/');

	$h = new Header();
	$a = new Article(new Div(new Paragraph('This is a test'), 'testDiv'));
	$s = new Section($a);
	$f = new Footer(new Paragraph('Copyright'));
	$h1 = new Heading(1, 'Hello World!');
	$p = new Paragraph('of adding content in the form of an array.');

	$p->setId('test p');
	$h->addElement($h1);
	$a->addElement($p);

	echo $h->toHtml();
	echo $s->toHtml();
	echo $f->toHtml();