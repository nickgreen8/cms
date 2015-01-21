<?php
namespace Tests\Components\Html;

use N8G\Cms\Components\Html\Paragraph;

class ParagraphTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function testToString()
	{
		//Default element
		$p = new Paragraph();
		$this->assertEquals('Paragraph element\r\n', $p->toString());

		//Element with string content
		$p = new Paragraph('This is a test');
		$this->assertEquals('Paragraph element\r\n    Content: This is a test\r\n', $p->toString());
	}
}