<?php
namespace Tests\Components\Html;

use N8G\Grass\Components\Html\Paragraph,
	N8G\Grass\Components\Html\Span,
	N8G\Grass\Components\Html\Head,
	N8G\Grass\Components\Html\Body,
	N8G\Grass\Components\Html\Title;

class ParagraphIntegrationTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 * @dataProvider validElementsProvider
	 */
	public function contentOnInstantiation()
	{}

	/**
	 * @test
	 */
	public function setElementsTest()
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
			array('element' => new Span('This is a Test'))
		);
	}

	public function invalidElementsProvider()
	{
		return array(
			array('element' => new Anchor()),
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
			array('element' => new Image()),
			array('element' => new Input()),
			array('element' => new Link()),
			array('element' => new ListItem()),
			array('element' => new Meta()),
			array('element' => new Option()),
			array('element' => new OrderedList()),
			array('element' => new PageBreak()),
			array('element' => new Paragraph()),
			array('element' => new Script()),
			array('element' => new Section()),
			array('element' => new Select()),
			array('element' => new Span()),
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