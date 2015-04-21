<?php
namespace Tests\Integration\Components\Html;

use N8G\Grass\Components\Html\Anchor,
	N8G\Grass\Components\Html\Article,
	N8G\Grass\Components\Html\Body,
	N8G\Grass\Components\Html\Button,
	N8G\Grass\Components\Html\Div,
	N8G\Grass\Components\Html\Fieldset,
	N8G\Grass\Components\Html\Footer,
	N8G\Grass\Components\Html\Form,
	N8G\Grass\Components\Html\Head,
	N8G\Grass\Components\Html\Header,
	N8G\Grass\Components\Html\Heading,
	N8G\Grass\Components\Html\HorizontalRule,
	N8G\Grass\Components\Html\HtmlAbstract,
	N8G\Grass\Components\Html\Image,
	N8G\Grass\Components\Html\Input,
	N8G\Grass\Components\Html\Link,
	N8G\Grass\Components\Html\ListItem,
	N8G\Grass\Components\Html\Meta,
	N8G\Grass\Components\Html\Option,
	N8G\Grass\Components\Html\OrderedList,
	N8G\Grass\Components\Html\PageBreak,
	N8G\Grass\Components\Html\Paragraph,
	N8G\Grass\Components\Html\Script,
	N8G\Grass\Components\Html\Section,
	N8G\Grass\Components\Html\Select,
	N8G\Grass\Components\Html\Span,
	N8G\Grass\Components\Html\Style,
	N8G\Grass\Components\Html\Table,
	N8G\Grass\Components\Html\TableBody,
	N8G\Grass\Components\Html\TableCell,
	N8G\Grass\Components\Html\TableHeader,
	N8G\Grass\Components\Html\TableHeading,
	N8G\Grass\Components\Html\TableRow,
	N8G\Grass\Components\Html\Title,
	N8G\Grass\Components\Html\UnorderedList,
	N8G\Grass\Exceptions\Components\ElementInvalidException;

class ParagraphIntegrationTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 * @dataProvider validElementsProvider
	 */
	public function contentOnInstantiationValid($element)
	{
		$p = new Paragraph($element);
	}
	/**
	 * @test
	 * @dataProvider invalidElementsProvider
	 * @expectedException ElementInvalidException
	 */
	public function contentOnInstantiationInvalid($element)
	{
		$p = new Paragraph($element);
	}

	/**
	 * @test
	 */
	public function setValidElementsTest()
	{}

	/**
	 * @test
	 */
	public function setInvalidElementsTest()
	{}

	/**
	 * @test
	 * @dataProvider validElementsProvider
	 */
	public function addValidElementTest($element)
	{
		$p = new Paragraph();
		$p->addElement($element);
		$this->assertContains();
	}

	/**
	 * @test
	 * @dataProvider invalidElementsProvider
	 * @expectedException ElementInvalidException
	 */
	public function addInvalidElementTest($element)
	{
		$p = new Paragraph();
		$this->assertEquals(false, $p->addElement($element));
	}

	// Data Providers

	public function validElementsProvider()
	{
		return array(
			array('element' => new Anchor()),
			array('element' => new Image()),
			array('element' => new PageBreak()),
			array('element' => new Span())
		);
	}

	public function invalidElementsProvider()
	{
		return array(
			array('element' => new Article()),
			array('element' => new Body()),
			array('element' => new Div()),
			array('element' => new Fieldset()),
			array('element' => new Footer()),
			array('element' => new Form()),
			array('element' => new Button()),
			array('element' => new Head()),
			array('element' => new Header()),
			array('element' => new Heading()),
			array('element' => new HorizontalRule()),
			array('element' => new Input()),
			array('element' => new Link()),
			array('element' => new ListItem()),
			array('element' => new Meta()),
			array('element' => new Option()),
			array('element' => new OrderedList()),
			array('element' => new Paragraph()),
			array('element' => new Script()),
			array('element' => new Section()),
			array('element' => new Select()),
			array('element' => new Style()),
			array('element' => new Table()),
			array('element' => new TableBody()),
			array('element' => new TableCell()),
			array('element' => new TableHeader()),
			array('element' => new TableHeading()),
			array('element' => new TableRow()),
			array('element' => new Title()),
			array('element' => new UnorderedList())
		);
	}
}