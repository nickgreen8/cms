<?php
namespace Test\Components\HTML;

use Components\HTML\Paragraph,
	\PHPUnit_Extensions_Story_TestCase;

class ParagraphTest extends \PHPUnit_Framework_TestCase
{
	public function setUp() { }
	public function tearDown() { }

	public function testToString()
	{
		//Default element
		$p = new Paragraph();
		$this->assertEquals('Paragraph element\r\n', $p->toString());

		//Element with string content
		$p = new Paragraph('This is a test');
		$this->assertEquals('Paragraph element\r\n Content: This is a test\r\n', $p->toString());
	}
}