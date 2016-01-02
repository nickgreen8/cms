<?php
namespace Tests\Unit\Components\Html;

use N8G\Grass\Components\Html\Paragraph;
use N8G\Utils\Log;

class ParagraphTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Sets up the test class.
	 */
	public static function setUpBeforeClass()
	{
		date_default_timezone_set('Europe/London');
		Log::init('tests/fixtures/logs/', 'testsLog.log');
	}

    /**
     * Cleans up after a test
     */
    public static function tearDownAfterClass()
    {
		unlink('tests/fixtures/logs/testsLog.log');
		touch('tests/fixtures/logs/testsLog.log');
    }

	// Tests

	/**
	 * Ensures that the class can be created as expected.
	 *
	 * @test
	 * @dataProvider instantiationProvider
	 *
	 * @param  mixed $content    The value for content from the data provider
	 * @param  mixed $id         The value for id from the data provider
	 * @param  mixed $elements   The value for elements from the data provider
	 * @param  mixed $attributes The value for attributes from the data provider
	 * @return void
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
	 * Tests that id there is an invalid parameter passed on instantiation, there is a valid error thrown.
	 *
	 * @test
	 * @dataProvider invalidInstantiationProvider
	 * @expectedException PHPUnit_Framework_Error
	 *
	 * @param  mixed $content    The value for content from the data provider
	 * @param  mixed $id         The value for id from the data provider
	 * @param  mixed $elements   The value for elements from the data provider
	 * @param  mixed $attributes The value for attributes from the data provider
	 * @return void
	 */
	public function testInvalidInstantiation($content, $id, $elements, $attributes)
	{
		$p = new Paragraph($content, $id, $elements, $attributes);
	}

	/**
	 * Tests that content can be set on the object.
	 *
	 * @test
	 *
	 * @return void
	 */
	public function testSetContent()
	{
		$p = new Paragraph();

		$p->setContent('This is a test');
		$this->assertEquals('This is a test', $p->getContent());
	}

	/**
	 * Ensures that the ID can be set successfully.
	 *
	 * @test
	 *
	 * @return void
	 */
	public function testSetId()
	{
		$p = new Paragraph();
		$p->setId('test');

		$this->assertEquals('test', $p->getId());
	}

	/**
	 * Checks that you can set the elements within the class.
	 *
	 * @test
	 *
	 * @return void
	 */
	public function testSetElements()
	{
		throw new \Exception('No tests implemented.');
	}

	/**
	 * Checks that you can get the elements within the class.
	 *
	 * @test
	 *
	 * @return void
	 */
	public function testGetElements()
	{
		throw new \Exception('No tests implemented.');
	}

	/**
	 * @test
	 * @dataProvider addElementProvider
	 *
	 * @param mixed   $element The element to be added to the component
	 * @param boolean $valid   Whether the element is valid
	 * @param array   $params  Any params that need passing to the child
	 * @return void
	 */
	public function testAddElements($element, $valid, $params = null)
	{
		$parent = new Paragraph();
		$obj    = sprintf('N8G\Grass\Components\Html\%s', $element);
		if (class_exists($obj)) {
			if (isset($params)) {
				$child  = new $obj(...$params);
			} else {
				$child  = new $obj();
			}
		} else {
			$child = $element;
		}
		$type  = gettype($child);

		$parent->addElement($child);

		$elements = $parent->getElements();

		if ($valid) {
			$this->assertCount(1, $elements);
			$this->assertInternalType($type, $elements[0]);
		} else {
			$this->assertCount(0, $elements);
		}
	}

	/**
	 * Checks that attributes can be set on the element.
	 *
	 * @test
	 *
	 * @return void
	 */
	public function testSetAttributes()
	{
		throw new \Exception('No tests implemented.');
	}

	/**
	 * Checks that attributes can be set on the element.
	 *
	 * @test
	 *
	 * @return void
	 */
	public function testGetAttributes()
	{
		throw new \Exception('No tests implemented.');
	}

	/**
	 * Tests the string return for the component.
	 *
	 * @test
	 * @dataProvider toStringProvider
	 *
	 * @param  mixed  $content    The value for content from the data provider
	 * @param  mixed  $id         The value for id from the data provider
	 * @param  mixed  $elements   The value for elements from the data provider
	 * @param  mixed  $attributes The value for attributes from the data provider
	 * @param  string $fixture    The description to compare with
	 * @return void
	 */
	public function testToString($content, $id, $elements, $attributes, $fixture)
	{
		//Default element
		$p = new Paragraph($content, $id, $elements, $attributes);
		$this->assertEquals($fixture, $p->toString());
	}

	/**
	 * Tests that the class builds the HTML correctly.
	 *
	 * @test
	 * @dataProvider toHtmlProvider
	 *
	 * @param  mixed  $content    The value for content from the data provider
	 * @param  mixed  $id         The value for id from the data provider
	 * @param  mixed  $elements   The value for elements from the data provider
	 * @param  mixed  $attributes The value for attributes from the data provider
	 * @param  string $fixture    The description to compare with
	 * @return void
	 */
	public function testToHtml($content, $id, $elements, $attributes, $fixture)
	{
		$p = new Paragraph($content, $id, $elements, $attributes);
		$this->assertEquals($fixture, $p->toHtml());
	}

	// Data providers

	/**
	 * Instantiation tests provider.
	 *
	 * @return array Data to run the tests with
	 */
	public function instantiationProvider()
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

	/**
	 * To String tests provider.
	 *
	 * @return array Data to run the tests with
	 */
	public function toStringProvider()
	{
		$data = $this->genericDataProvider();

		//Add fixtures
		$data[0]['fixture'] = 'Paragraph element';
		$data[1]['fixture'] = 'Paragraph element\n\r    Content: Test';
		$data[2]['fixture'] = 'Paragraph element\n\r    ID: test';
		$data[3]['fixture'] = 'Paragraph element\n\r    Elements:\n\rPHPUnit is...fun!';
		$data[4]['fixture'] = 'Paragraph element\n\r    Attributes: class=test';
		$data[5]['fixture'] = 'Paragraph element\n\r    Content: 1';
		$data[6]['fixture'] = 'Paragraph element\n\r    ID: 1';

		return $data;
	}

	/**
	 * To HTML test provider.
	 *
	 * @return array Data to run the tests with
	 */
	public function toHtmlProvider()
	{
		$data = $this->genericDataProvider();

		//Add fixtures
		$data[0]['fixture'] = '<p></p>';
		$data[1]['fixture'] = '<p>Test</p>';
		$data[2]['fixture'] = '<p id="test"></p>';
		$data[3]['fixture'] = '<p>PHPUnit is...fun!</p>';
		$data[4]['fixture'] = '<p class="test"></p>';
		$data[5]['fixture'] = '<p>1</p>';
		$data[6]['fixture'] = '<p id="1"></p>';

		return $data;
	}

	/**
	 * Generic data provider that is consumed by other providers.
	 *
	 * @return array Data to run the tests with
	 */
	private function genericDataProvider()
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
				'elements'		=>	array('PHPUnit is...', 'fun!'),
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
			)
		);
	}

	/**
	 * Provider for the invalid instantiation.
	 *
	 * @return array Data to run the tests with
	 */
	public function invalidInstantiationProvider()
	{
		return array(
			array(
				'content'		=>	null,
				'id'			=>	null,
				'elements'		=>	'test',
				'attributes'	=>	array()
			),
			array(
				'content'		=>	null,
				'id'			=>	null,
				'elements'		=>	1,
				'attributes'	=>	array()
			),
			array(
				'content'		=>	null,
				'id'			=>	null,
				'elements'		=>	true,
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
			),
			array(
				'content'		=>	null,
				'id'			=>	null,
				'elements'		=>	array(),
				'attributes'	=>	false
			)
		);
	}

	/**
	 * Data provider for the add element tests.
	 *
	 * @return array Data to run the tests with
	 */
	public function addElementProvider()
	{
		return array(
			array(
				'element' => 1,
				'valid'   => true
			),
			array(
				'element' => 'Test',
				'valid'   => true
			),
			array(
				'element' => 'Anchor',
				'valid'   => true
			),
			array(
				'element' => 'Article',
				'valid'   => false
			),
			array(
				'element' => 'Body',
				'valid'   => false
			),
			array(
				'element' => 'Div',
				'valid'   => false
			),
			array(
				'element' => 'Fieldset',
				'valid'   => false
			),
			array(
				'element' => 'Footer',
				'valid'   => false
			),
			array(
				'element' => 'Form',
				'valid'   => false
			),
			array(
				'element' => 'Button',
				'valid'   => false
			),
			array(
				'element' => 'Head',
				'valid'   => false
			),
			array(
				'element' => 'Header',
				'valid'   => false
			),
			array(
				'element' => 'Heading',
				'valid'   => false,
				'params'  => [1]
			),
			array(
				'element' => 'HorizontalRule',
				'valid'   => false
			),
			array(
				'element' => 'Image',
				'valid'   => true
			),
			array(
				'element' => 'Input',
				'valid'   => false
			),
			array(
				'element' => 'Link',
				'valid'   => false
			),
			array(
				'element' => 'ListItem',
				'valid'   => false
			),
			array(
				'element' => 'Meta',
				'valid'   => false
			),
			array(
				'element' => 'Option',
				'valid'   => false
			),
			array(
				'element' => 'OrderedList',
				'valid'   => false
			),
			array(
				'element' => 'PageBreak',
				'valid'   => true
			),
			array(
				'element' => 'Paragraph',
				'valid'   => false
			),
			array(
				'element' => 'Script',
				'valid'   => false
			),
			array(
				'element' => 'Section',
				'valid'   => false
			),
			array(
				'element' => 'Select',
				'valid'   => false
			),
			array(
				'element' => 'Span',
				'valid'   => true
			),
			array(
				'element' => 'Style',
				'valid'   => false
			),
			array(
				'element' => 'Table',
				'valid'   => false
			),
			array(
				'element' => 'TableBody',
				'valid'   => false
			),
			array(
				'element' => 'TableCell',
				'valid'   => false
			),
			array(
				'element' => 'TableHeader',
				'valid'   => false
			),
			array(
				'element' => 'TableHeading',
				'valid'   => false
			),
			array(
				'element' => 'TableRow',
				'valid'   => false
			),
			array(
				'element' => 'Title',
				'valid'   => false
			),
			array(
				'element' => 'UnorderedList',
				'valid'   => false
			)
		);
	}
}