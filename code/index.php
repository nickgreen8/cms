<?php
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

require_once __DIR__ . '/vendor/autoload.php';

//Look at bootstrapping here
Log::init(__DIR__ . '/logs/');

	$head = new Head(array(new Title('Integration Testing'), new Style('body { font-family: Verdana, Geneva, sans-serif; }')));

	$page = new Body();

	//Begin integration test
	$page->addElement(new Heading('1', 'Integration Testing'));
	$page->addElement(new Heading('2', 'Config Class'));

	Config::setItem('test', 'this is a test');
	Config::setItem('name', 'Nick Green');

	$page->addElement(new Paragraph(Config::getItem('test')));
	$page->addElement(new Paragraph(Config::getItem('name')));

	//Output page
	echo $head->toHtml();
	echo $page->toHtml();