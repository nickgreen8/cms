<?php
namespace Tests\Unit\Components\Html;

use N8G\Grass\Components\Html\Paragraph,
	N8G\Utils\Log;

class ParagraphTests extends \PHPUnit_Framework_TestCase
{
	/**
	 * Sets up the test class.
	 */
	public static function setUpBeforeClass()
	{
		date_default_timezone_set('Europe/London');
		Log::init('tests/fixtures/logs/', 'testsLog.log');
	}

	// Tests

	/**
	 * @test
	 * @dataProvider paragraphProvider
	 */
	public function testInstantiation($content, $id, $elements, $attributes)
	{
		//No arguments
		$p = new Paragraph();
		$this->assertInstanceOf('N8G\Grass\Components\Html\Paragraph', $p);

		//Just content
		$p = new Paragraph($content);
		$this->assertInstanceOf('N8G\Grass\Components\Html\Paragraph', $p);

		//Content and ID
		$p = new Paragraph($content, $id);
		$this->assertInstanceOf('N8G\Grass\Components\Html\Paragraph', $p);

		//Content, ID and elements
		$p = new Paragraph($content, $id, $elements);
		$this->assertInstanceOf('N8G\Grass\Components\Html\Paragraph', $p);

		//Content, ID, Elements and attributes
		$p = new Paragraph($content, $id, $elements, $attributes);
		$this->assertInstanceOf('N8G\Grass\Components\Html\Paragraph', $p);
	}

	/**
	 * @test
	 */
	public function setContent()
	{
		$p = new Paragraph();

		$p->setContent('This is a test');
		$this->assertEquals('This is a test', $p->getContent());
	}

	/**
	 * @test
	 */
	public function setId()
	{
		$p = new Paragraph();
		$p->setId('test');

		$this->assertEquals('test', $p->getId());
	}

	/**
	 * @test
	 */
	public function setElements()
	{
		$p = new Paragraph();
		$p->setId('test');

		$this->assertEquals('test', $p->getId());
	}

	/**
	 * @test
	 * @expectedException
	 */
	public function setAttributes()
	{
		$p = new Paragraph();
		$p->setId('test');

		$this->assertEquals('test', $p->getId());
	}

	/**
	 * @test
	 * @dataProvider paragraphStringProvider
	 */
	public function testToString($content, $id, $elements, $attributes, $fixture)
	{
		//Default element
		$p = new Paragraph($content, $id, $elements, $attributes);
		$this->assertEquals($p->toHtml(), $fixture);
	}

	/**
	 * @test
	 * @dataProvider paragraphHtmlProvider
	 */
	public function testToHtml($content, $id, $elements, $attributes, $fixture)
	{
		$p = new Paragraph($content, $id, $elements, $attributes);
		$this->assertEquals($p->toHtml(), $fixture);
	}

	// Data providers

	public function paragraphProvider()
	{
		return array(
			array(
				'content'		=>	'This is a test',
				'id'			=>	'test',
				'elements'		=>	array(),
				'attributes'	=>	array()
			)
		);
	}

	public function paragraphStringProvider()
	{
		$data = $this->paragraphDataProvider();

		//Add fixtures
		$data[0]['fixture'] = '<p></p>';
		$data[1]['fixture'] = '<p>Test</p>';
		$data[2]['fixture'] = '<p id="test"></p>';
		$data[3]['fixture'] = '<p>PHPUnit is...fun!</p>';
		$data[4]['fixture'] = '<p class="test"></p>';
		$data[5]['fixture'] = '<p>1</p>';
		$data[6]['fixture'] = '<p id="1"></p>';
		$data[7]['fixture'] = '<p></p>';
		$data[8]['fixture'] = '<p></p>';
		$data[9]['fixture'] = '<p></p>';
		$data[10]['fixture'] = '<p></p>';

		return $data;
	}

	public function paragraphHtmlProvider()
	{
		$data = $this->paragraphDataProvider();

		//Add fixtures
		$data[0]['fixture'] = '<p></p>';
		$data[1]['fixture'] = '<p>Test</p>';
		$data[2]['fixture'] = '<p id="test"></p>';
		$data[3]['fixture'] = '<p>PHPUnit is...fun!</p>';
		$data[4]['fixture'] = '<p class="test"></p>';
		$data[5]['fixture'] = '<p>1</p>';
		$data[6]['fixture'] = '<p id="1"></p>';
		$data[7]['fixture'] = '<p></p>';
		$data[8]['fixture'] = '<p></p>';
		$data[9]['fixture'] = '<p></p>';
		$data[10]['fixture'] = '<p></p>';

		return $data;
	}

	private function paragraphDataProvider()
	{
		return array(
			array(
				'content'		=>	null,
				'id'			=>	null,
				'elements'		=>	array(),
				'attributes'	=>	array()
			),
			array(
				'content'		=>	'Test',
				'id'			=>	null,
				'elements'		=>	array(),
				'attributes'	=>	array()
			),
			array(
				'content'		=>	null,
				'id'			=>	'test',
				'elements'		=>	array(),
				'attributes'	=>	array()
			),
			array(
				'content'		=>	null,
				'id'			=>	null,
				'elements'		=>	array(array('PHPUnit is...', 'fun!')),
				'attributes'	=>	array()
			),
			array(
				'content'		=>	null,
				'id'			=>	null,
				'elements'		=>	array(),
				'attributes'	=>	array('class' => 'test')
			),
			array(
				'content'		=>	1,
				'id'			=>	null,
				'elements'		=>	array(),
				'attributes'	=>	array()
			),
			array(
				'content'		=>	null,
				'id'			=>	1,
				'elements'		=>	array(),
				'attributes'	=>	array()
			),
			array(
				'content'		=>	null,
				'id'			=>	null,
				'elements'		=>	'test',
				'attributes'	=>	array()
			),
			array(
				'content'		=>	null,
				'id'			=>	'test',
				'elements'		=>	1,
				'attributes'	=>	array()
			),
			array(
				'content'		=>	null,
				'id'			=>	null,
				'elements'		=>	array(),
				'attributes'	=>	'test'
			),
			array(
				'content'		=>	null,
				'id'			=>	null,
				'elements'		=>	array(),
				'attributes'	=>	1
			)
		);
	}
}