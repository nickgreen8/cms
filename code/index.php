<?php
namespace Website;

use Components\HTML\Header,
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
	$h2 = new Heading(2, 'Unordered List');
	$h3 = new Heading(3, 'Ordered List');
	$p = new Paragraph('of adding content in the form of an array.');
	$ul = new UnorderedList();
	$ol = new OrderedList();
	$table = new Table();
	$thead = new TableHeader();
	$tbody = new TableBody();
	$row1 = new TableRow();
	$row2 = new TableRow();

	$items = array(new ListItem('Item 1'), new ListItem('Item 2'), new ListItem('Item 3'));

	$p->setId('test p');

	$h->addElement($h1);
	$a->addElement($p);
	$s->addElement($h2);
	$s->addElement($ul);
	$s->addElement($h3);
	$s->addElement($ol);
	$a->addElement($table);
	$table->addElement($thead);
	$table->addElement($tbody);
	$thead->addElement($row1);
	$tbody->addElement($row2);

	$ul->setElements($items);
	$ol->setElements($items);

	echo $h->toHtml();
	echo $s->toHtml();
	echo $f->toHtml();