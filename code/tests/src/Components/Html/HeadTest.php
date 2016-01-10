<?php
namespace Tests\Unit\Components\Html;

use N8G\Grass\Components\Html\Head;
use N8G\Utils\Log;

class HeadTest extends \PHPUnit_Framework_TestCase
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
		$element = new Head();
		$this->assertInstanceOf('N8G\Grass\Components\Html\Head', $element);

		//Just content
		$element = new Head($content);
		$this->assertInstanceOf('N8G\Grass\Components\Html\Head', $element);

		//Content and ID
		$element = new Head($content, $id);
		$this->assertInstanceOf('N8G\Grass\Components\Html\Head', $element);

		//Content, ID and elements
		$element = new Head($content, $id, $elements);
		$this->assertInstanceOf('N8G\Grass\Components\Html\Head', $element);

		//Content, ID, Elements and attributes
		$element = new Head($content, $id, $elements, $attributes);
		$this->assertInstanceOf('N8G\Grass\Components\Html\Head', $element);
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
		$element = new Head($content, $id, $elements, $attributes);
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
		$element = new Head();

		$element->setContent('This is a test');
		$this->assertEquals('This is a test', $element->getContent());
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
		$element = new Head();
		$element->setId('test');

		$this->assertEquals('test', $element->getId());
	}

	/**
	 * Checks that you can set the elements within the class.
	 *
	 * @test
	 * @dataProvider elementProvider
	 *
	 * @param mixed $elements The element to be added to the component
	 * @param array $expected The array that is expected to have been set
	 *
	 * @return void
	 */
	public function testSetElements($elements, $expected)
	{
		//Format the elements array
		foreach ($elements as $key => $value) {
			if (is_array($value) && $value[0] === 'object') {
				$obj = sprintf('N8G\Grass\Components\Html\%s', $value[1]);
				if (class_exists($obj)) {
					if (isset($value[2])) {
						$class = new $obj(...$value[2]);
					} else {
						$class = new $obj();
					}
				}

				//Swap in the new value
				$elements[$key] = $class;
			}
		}

		$element = new Head();
		$element->setElements($elements);

		$this->assertEquals($expected, $this->getPrivateProperty($element, 'elements'));
	}

	/**
	 * Checks that you can get the elements within the class.
	 *
	 * @test
	 * @dataProvider elementProvider
	 *
	 * @param mixed $elements The element to be added to the component
	 * @param array $expected The array that is expected to have been set
	 *
	 * @return void
	 */
	public function testGetElements($elements, $expected)
	{
		//Format the elements array
		foreach ($elements as $key => $value) {
			if (is_array($value) && $value[0] === 'object') {
				$obj = sprintf('N8G\Grass\Components\Html\%s', $value[1]);
				if (class_exists($obj)) {
					if (isset($value[2])) {
						$class = new $obj(...$value[2]);
					} else {
						$class = new $obj();
					}
				}

				//Swap in the new value
				$elements[$key] = $class;
			}
		}

		$element = new Head();
		$element->setElements($elements);

		$this->assertEquals($expected, $element->getElements());
	}

	/**
	 * This is the test for adding elements. Some of the elements will be added but some will be invalid and they will
	 * not be added.
	 *
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
		$parent = new Head();
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
		$element = new Head();
		$element->setAttributes(array('style', 'text-align: center;'));
		$this->assertEquals(array('style', 'text-align: center;'), $this->getPrivateProperty($element, 'attributes'));
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
		$element = new Head('This is a test.', 'test', array(), array('class', 'test'));
		$this->assertEquals(array('class', 'test'), $element->getAttributes());

		$element->setAttributes(array('style', 'text-align: center;'));
		$this->assertEquals(array('style', 'text-align: center;'), $element->getAttributes());
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
		$element = new Head($content, $id, $elements, $attributes);
		$this->assertEquals($fixture, $element->toString());
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
		$element = new Head($content, $id, $elements, $attributes);
		$this->assertEquals($fixture, $element->toHtml());
	}

	/**
	 * Tests that the add attribute function works as expected.
	 *
	 * @test
	 *
	 * @return void
	 */
	public function testAddAttribute()
	{
		$element = new Head();

		//Add valid attribute
		$element->addAttribute(array('class' => 'test'));
		$this->assertEquals(array('class' => 'test'), $this->getPrivateProperty($element, 'attributes'));

		//Add second valid attribute
		$element->addAttribute(array('style' => 'text-align: center;'));
		$this->assertEquals(array('class' => 'test', 'style' => 'text-align: center;'), $this->getPrivateProperty($element, 'attributes'));

		//Add invalid attribute
		$this->setExpectedException('N8G\Grass\Exceptions\Components\AttributeInvalidException');
		$element->addAttribute(array('title'));
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
		$data[0]['fixture'] = 'Head element';
		$data[1]['fixture'] = 'Head element\n\r    Content: Test';
		$data[2]['fixture'] = 'Head element\n\r    ID: test';
		$data[3]['fixture'] = 'Head element\n\r    Elements:\n\rPHPUnit is...fun!';
		$data[4]['fixture'] = 'Head element\n\r    Attributes: class=test';
		$data[5]['fixture'] = 'Head element\n\r    Content: 1';
		$data[6]['fixture'] = 'Head element\n\r    ID: 1';

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
		$data[0]['fixture'] = '<head></head>';
		$data[1]['fixture'] = '<head>Test</head>';
		$data[2]['fixture'] = '<head id="test"></head>';
		$data[3]['fixture'] = '<head>PHPUnit is...fun!</head>';
		$data[4]['fixture'] = '<head class="test"></head>';
		$data[5]['fixture'] = '<head>1</head>';
		$data[6]['fixture'] = '<head id="1"></head>';

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
				'element' => 'Anchor',
				'valid'   => false
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
				'valid'   => false
			),
			array(
				'element' => 'Input',
				'valid'   => false
			),
			array(
				'element' => 'Link',
				'valid'   => true
			),
			array(
				'element' => 'ListItem',
				'valid'   => false
			),
			array(
				'element' => 'Meta',
				'valid'   => true
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
				'valid'   => false
			),
			array(
				'element' => 'Paragraph',
				'valid'   => false
			),
			array(
				'element' => 'Script',
				'valid'   => true
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
				'valid'   => false
			),
			array(
				'element' => 'Style',
				'valid'   => true
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
				'valid'   => true
			),
			array(
				'element' => 'UnorderedList',
				'valid'   => false
			)
		);
	}

	/**
	 * Data provider for the set/get element tests.
	 *
	 * @return array Data to run the tests with
	 */
	public function elementProvider() {
		return array(
			array(
				'elements' => array('Test'),
				'expected' => array('Test')
			),
			array(
				'elements' => array(['object', 'Head']),
				'expected' => array()
			),
			array(
				'elements' => array('Test', ['object', 'Head']),
				'expected' => array('Test')
			)
		);
	}

	// Private Helpers

	/**
	 * Call protected/private method of a class.
	 *
	 * @param  object &$object Instantiated object that we will run method on.
	 * @param  string $method  Method name to call
	 * @param  array  $params  Array of parameters to pass into method.
	 * @return mixed           Method return.
	 */
	private function invokeMethod(&$object, $method, array $params = array())
	{
		$reflection = new \ReflectionClass(get_class($object));
		$method = $reflection->getMethod($method);
		$method->setAccessible(true);

		return $method->invokeArgs($object, $params);
	}

	/**
	 * Get a protected/private property of a class.
	 *
	 * @param  object &$object  Instantiated object that we will run method on.
	 * @param  string $property The property to get
	 * @return mixed            Property value
	 */
	private function getPrivateProperty(&$object, $property) {
		$reflection = new \ReflectionClass(get_class($object));
		$property = $reflection->getProperty($property);
		$property->setAccessible(true);
 
		return $property->getValue($object);
	}
}