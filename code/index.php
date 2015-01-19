<?php
namespace N8G\Cms;

use N8G\Cms\Components\Html\Head,
	N8G\Cms\Components\Html\Title,
	N8G\Cms\Components\Html\Meta,
	N8G\Cms\Components\Html\Link,
	N8G\Cms\Components\Html\Script,
	N8G\Cms\Components\Html\Style,
	N8G\Cms\Components\Html\Body,
	N8G\Cms\Components\Html\Header,
	N8G\Cms\Components\Html\Footer,
	N8G\Cms\Components\Html\Section,
	N8G\Cms\Components\Html\Article,
	N8G\Cms\Components\Html\Div,
	N8G\Cms\Components\Html\Heading,
	N8G\Cms\Components\Html\Paragraph,
	N8G\Cms\Components\Html\UnorderedList,
	N8G\Cms\Components\Html\OrderedList,
	N8G\Cms\Components\Html\ListItem,
	N8G\Cms\Components\Html\Table,
	N8G\Cms\Components\Html\TableHeader,
	N8G\Cms\Components\Html\TableBody,
	N8G\Cms\Components\Html\TableRow,
	N8G\Cms\Components\Html\TableHeading,
	N8G\Cms\Components\Html\TableCell,
	N8G\Cms\Components\Html\Anchor,
	N8G\Cms\Components\Html\Image,
	N8G\Cms\Components\Html\Form,
	N8G\Cms\Components\Html\Fieldset,
	N8G\Cms\Components\Html\Input,
	N8G\Cms\Components\Html\Select,
	N8G\Cms\Components\Html\Option,
	N8G\Cms\Components\Html\Button,
	N8G\Cms\Components\Html\PageBreak,
	N8G\Cms\Components\Html\HorizontalRule,
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