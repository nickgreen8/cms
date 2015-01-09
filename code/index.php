<?php
namespace Website;

use Components\HTML\Head,
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

	$head = new Head('<title>Test</title>');
	$body = new Body('test');
	$h = new Header();
	$a = new Article(new Div(new Paragraph('This is a test'), 'testDiv'));
	$s = new Section($a);
	$f = new Footer(new Paragraph('Copyright'));
	$h1 = new Heading(1, 'Hello World!');
	$h2 = new Heading(2, 'Unordered List');
	$h3 = new Heading(3, 'Ordered List');
	$p = new Paragraph('of adding content in the form of an array.');
	$ul = new UnorderedList();
	$ol = new OrderedList();
	$table = new Table();
	$thead = new TableHeader();
	$tbody = new TableBody();
	$row1 = new TableRow(new TableHeading('heading 1', null, array(), array('rowspan' => '2')));
	$row2 = new TableRow();
	$cell = new TableCell('cell 2', null, array(), array('colspan' => '2'));
	$image1 = new Image(null, array('src' => 'http://nickgreenweb.co.uk/media/images/logo.png', 'style' => 'width: 100px; height: auto;'));
	$image2 = new Image(null, array('alt' => 'Test', 'style' => 'width: 100px; height: auto;'));
	$form = new Form(null, 'testForm', array(), array('method' => 'post', 'action' => '#', 'enctype' => 'multipart/form-data'));
	$fieldset = new Fieldset();
	$input = new Input('name', array('type' => 'text', 'value' => 'test'));
	$select = new Select(null, 'test', array(), array('name' => 'test'));
	$option = new Option('test 1', 'test1', array(), array('value' => '1'));
	$button = new Button('Button', null, array(), array('type' => 'button'));

	$items = array(new ListItem('Item 1'), new ListItem('Item 2'), new ListItem('Item 3'));
	$cells = array(new TableCell('cell 1'), $cell, new TableCell('cell 3'));
	$options = array($option, new Option('test 2', 'test2', array(), array()), new Option('test 3', 'test3', array(), array('value' => '3')));

	$p->setId('test p');

	$ul->setElements($items);
	$ol->setElements($items);
	$row2->setElements($cells);

	$h->addElement($h1);
	$a->addElement($p);
	$s->addElement(new HorizontalRule());
	$s->addElement($h2);
	$s->addElement($ul);
	$s->addElement($h3);
	$s->addElement($ol);
	$a->addElement($table);
	$a->addElement(new Paragraph(array('Click Here: ', new Anchor('Broken Link', 'brokenLink'), ' or ', new Anchor('BBC', null, array(), array('href' => 'http://www.bbc.co.uk/')))));
	$a->addElement($image1);
	$a->addElement(new Image(null, array('style' => 'width: 100px; height: auto;')));
	$a->addElement($image2);
	$a->addElement(new Image('logo', array('src' => 'http://nickgreenweb.co.uk/media/images/logo.png', 'alt' => 'Test', 'style' => 'width: 100px; height: auto;')));
	$a->addElement($form);
	$a->addElement(new Form(null, 'testForm', array(), array('method' => 'post', 'action' => '#', 'enctype' => 'testFail')));
	$table->addElement($thead);
	$table->addElement($tbody);
	$thead->addElement($row1);
	$thead->addElement(new TableRow(new TableCell('Sub heading', null, array(), array('colspan' => '3'))));
	$tbody->addElement($row2);
	$row1->addElement(new TableHeading('heading 2'));
	$row1->addElement(new TableHeading('heading 3'));
	$row1->addElement(new TableHeading('heading 4'));
	$form->addElement($fieldset);
	$fieldset->addElement($input);
	$fieldset->addElement(new Input('username', array('name' => 'username', 'value' => 'test')));
	$fieldset->addElement(new Input('username', array('type' => 'password', 'name' => 'username', 'value' => 'test')));
	$fieldset->addElement($select);
	$fieldset->addElement(new Select(null, 'select', array(), array()));
	$fieldset->addElement($button);
	$fieldset->addElement(new Button('Reset', null, array(), array('type' => 'reset')));
	$fieldset->addElement(new Button('Submit', null, array(), array()));
	$s->addElement(new Paragraph(array('Line 1', new PageBreak(), 'Line 2')));

	$body->setElements(array($h, $s, $f));

	echo $head->toHtml();
	echo $body->toHtml();