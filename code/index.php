<?php
namespace N8G\Cms;

use Components\HTML\Head,
	Components\HTML\Title,
	Components\HTML\Meta,
	Components\HTML\Link,
	Components\HTML\Script,
	Components\HTML\Style,
	Components\HTML\Body,
	Components\HTML\Header,
	Components\HTML\Footer,
	Components\HTML\Section,
	Components\HTML\Article,
	Components\HTML\Div,
	Components\HTML\Heading,
	Components\HTML\Paragraph,
	Components\HTML\UnorderedList,
	Components\HTML\OrderedList,
	Components\HTML\ListItem,
	Components\HTML\Table,
	Components\HTML\TableHeader,
	Components\HTML\TableBody,
	Components\HTML\TableRow,
	Components\HTML\TableHeading,
	Components\HTML\TableCell,
	Components\HTML\Anchor,
	Components\HTML\Image,
	Components\HTML\Form,
	Components\HTML\Fieldset,
	Components\HTML\Input,
	Components\HTML\Select,
	Components\HTML\Option,
	Components\HTML\Button,
	Components\HTML\PageBreak,
	Components\HTML\HorizontalRule,
	Utils\Log,
	Utils\Config;

error_reporting(E_ALL);
date_default_timezone_set('Europe/London');

require_once __DIR__ . '/library/autoload.php';
$loader = Autoload::getInstance();
Log::init(__DIR__ . '/library/Logs/');

	$head = new Head(new Title('Integration Testing'));

	$page = new Body();
	$page->addElement(new Heading('1', 'Integration Testing'));
	$page->addElement(new Heading('2', 'Config Class'));

	echo $head->toHtml();

	Config::setItem('test', 'this is a test');
	Config::setItem('name', 'Nick Green');

	$page->addElement(new Paragraph(Config::getItem('test')));
	$page->addElement(new Paragraph(Config::getItem('name')));

	echo $page->toHtml();