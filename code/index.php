<?php
namespace Website;

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
	Utils\Log;

error_reporting(E_ALL);
date_default_timezone_set('Europe/London');

require_once __DIR__ . '/library/autoload.php';
$loader = Autoload::getInstance();
Log::init(__DIR__ . '/library/Logs/');

	$page = new Body();
	$page->addElement(new Heading('1', 'Adding Elements'));

	$element = new Head();

	$head = new Head();
	$title = new Title();
	$meta = new Meta();
	$link = new Link();
	$script = new Script();
	$style = new Style();
	$body = new Body();
	$header = new Header();
	$footer = new Footer();
	$section = new Section();
	$article = new Article();
	$div = new Div();
	$heading = new Heading();
	$paragraph = new Paragraph();
	$unorderedList = new UnorderedList();
	$orderedList = new OrderedList();
	$listItem = new ListItem();
	$table = new Table();
	$tableHeader = new TableHeader();
	$tableBody = new TableBody();
	$tableRow = new TableRow();
	$tableHeading = new TableHeading();
	$tableCell = new TableCell();
	$anchor = new Anchor();
	$image = new Image();
	$form = new Form();
	$fieldset = new Fieldset();
	$input = new Input();
	$select = new Select();
	$option = new Option();
	$button = new Button();
	$pageBreak = new PageBreak();
	$horizontalRule = new HorizontalRule();

	$element->addElement($head);
	$element->addElement($title);
	$element->addElement($meta);
	$element->addElement($link);
	$element->addElement($script);
	$element->addElement($style);
	$element->addElement($body);
	$element->addElement($header);
	$element->addElement($footer);
	$element->addElement($section);
	$element->addElement($article);
	$element->addElement($div);
	$element->addElement($heading);
	$element->addElement($paragraph);
	$element->addElement($unorderedList);
	$element->addElement($orderedList);
	$element->addElement($listItem);
	$element->addElement($table);
	$element->addElement($tableHeader);
	$element->addElement($tableBody);
	$element->addElement($tableRow);
	$element->addElement($tableHeading);
	$element->addElement($tableCell);
	$element->addElement($anchor);
	$element->addElement($image);
	$element->addElement($form);
	$element->addElement($fieldset);
	$element->addElement($input);
	$element->addElement($select);
	$element->addElement($option);
	$element->addElement($button);
	$element->addElement($pageBreak);
	$element->addElement($horizontalRule);

	$page->addElement(new Heading('3', 'Elements Added'));
	echo $page->toHtml();