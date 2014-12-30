<?php
require_once('../../Components/HTML/Paragraph.php');

class ParagraphTest extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		$this->paragraph = new Paragraph();
	}

	public function testToString()
	{
		$p = new Paragraph();
		$this->assertEquals('', $p->toString());
	}
}