<?php
namespace N8G\Cms;

use N8G\Cms\Components\HTML\Head,
	N8G\Cms\Components\HTML\Title,
	N8G\Cms\Components\HTML\Meta,
	N8G\Cms\Components\HTML\Link,
	N8G\Cms\Components\HTML\Script,
	N8G\Cms\Components\HTML\Style,
	N8G\Cms\Components\HTML\Body,
	N8G\Cms\Components\HTML\Header,
	N8G\Cms\Components\HTML\Footer,
	N8G\Cms\Components\HTML\Section,
	N8G\Cms\Components\HTML\Article,
	N8G\Cms\Components\HTML\Div,
	N8G\Cms\Components\HTML\Heading,
	N8G\Cms\Components\HTML\Paragraph,
	N8G\Cms\Components\HTML\UnorderedList,
	N8G\Cms\Components\HTML\OrderedList,
	N8G\Cms\Components\HTML\ListItem,
	N8G\Cms\Components\HTML\Table,
	N8G\Cms\Components\HTML\TableHeader,
	N8G\Cms\Components\HTML\TableBody,
	N8G\Cms\Components\HTML\TableRow,
	N8G\Cms\Components\HTML\TableHeading,
	N8G\Cms\Components\HTML\TableCell,
	N8G\Cms\Components\HTML\Anchor,
	N8G\Cms\Components\HTML\Image,
	N8G\Cms\Components\HTML\Form,
	N8G\Cms\Components\HTML\Fieldset,
	N8G\Cms\Components\HTML\Input,
	N8G\Cms\Components\HTML\Select,
	N8G\Cms\Components\HTML\Option,
	N8G\Cms\Components\HTML\Button,
	N8G\Cms\Components\HTML\PageBreak,
	N8G\Cms\Components\HTML\HorizontalRule,
	N8G\Utils\Log,
	N8G\Utils\Config;

error_reporting(E_ALL);
date_default_timezone_set('Europe/London');

// require_once __DIR__ . '/src/autoload.php';
// $loader = Autoload::getInstance();
Log::init(__DIR__ . '/Logs/');

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