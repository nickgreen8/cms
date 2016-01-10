<?php
namespace Tests\Unit\Components\Html;

use N8G\Grass\Components\Html\Form;
use N8G\Utils\Log;

class FormTest extends \PHPUnit_Framework_TestCase
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
		$element = new Form();
		$this->assertInstanceOf('N8G\Grass\Components\Html\Form', $element);

		//Just content
		$element = new Form($content);
		$this->assertInstanceOf('N8G\Grass\Components\Html\Form', $element);

		//Content and ID
		$element = new Form($content, $id);
		$this->assertInstanceOf('N8G\Grass\Components\Html\Form', $element);

		//Content, ID and elements
		$element = new Form($content, $id, $elements);
		$this->assertInstanceOf('N8G\Grass\Components\Html\Form', $element);

		//Content, ID, Elements and attributes
		$element = new Form($content, $id, $elements, $attributes);
		$this->assertInstanceOf('N8G\Grass\Components\Html\Form', $element);
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
		$element = new Form($content, $id, $elements, $attributes);
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
		$element = new Form();

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
		$element = new Form();
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

		$element = new Form();
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

		$element = new Form();
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
		$parent = new Form();
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
		$element = new Form();
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
		$element = new Form('This is a test.', 'test', array(), array('class', 'test'));
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
		$element = new Form($content, $id, $elements, $attributes);
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
		$element = new Form($content, $id, $elements, $attributes);
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
		$element = new Form();

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

	/**
	 * Tests the overloaded validate attributes function within the game class.
	 *
	 * @test
	 *
	 * @return void
	 */
	public function testValidateAttributes()
	{
		$element = new Form();

		//Test all the valid values of the Form
		$element->setAttributes(array('method' => 'post', 'action' => '/service', 'enctype' => 'multipart/form-data'));
		$this->invokeMethod($element, 'validateAttributes');

		$element->setAttributes(array('method' => 'post', 'action' => '/service', 'enctype' => 'application/x-www-form-urlencoded'));
		$this->invokeMethod($element, 'validateAttributes');

		$element->setAttributes(array('method' => 'post', 'action' => '/service', 'enctype' => 'text/plain'));
		$this->invokeMethod($element, 'validateAttributes');

		//Test all the valid values of the Form
		$element->setAttributes(array('method' => 'get', 'action' => '/service', 'enctype' => 'multipart/form-data'));
		$this->invokeMethod($element, 'validateAttributes');

		//Add invalid attribute
		$element = new Form();
		$this->setExpectedException('N8G\Grass\Exceptions\Components\ComponentMissingRequiredAttributesException');
		$this->invokeMethod($element, 'validateAttributes');

		$element->setAttributes(array('action' => '/service', 'enctype' => 'multipart/form-data'));
		$this->invokeMethod($element, 'validateAttributes');

		$element->setAttributes(array('method' => 'post', 'enctype' => 'multipart/form-data'));
		$this->invokeMethod($element, 'validateAttributes');

		$element->setAttributes(array('method' => 'post', 'action' => '/service'));
		$this->invokeMethod($element, 'validateAttributes');

		$element->setAttributes(array('enctype' => 'multipart/form-data'));
		$this->invokeMethod($element, 'validateAttributes');

		$element->setAttributes(array('enctype' => 'multipart/form-data'));
		$this->invokeMethod($element, 'validateAttributes');

		$element->setAttributes(array('action' => '/service'));
		$this->invokeMethod($element, 'validateAttributes');

		$element->setAttributes(array('method' => 'post'));
		$this->invokeMethod($element, 'validateAttributes');
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
		$data[0]['fixture'] = 'Form element\n\r    Attributes: method=post, action=/service, enctype=multipart/form-data';
		$data[1]['fixture'] = 'Form element\n\r    Content: Test\n\r    Attributes: method=post, action=/service, enctype=multipart/form-data';
		$data[2]['fixture'] = 'Form element\n\r    ID: test\n\r    Attributes: method=post, action=/service, enctype=multipart/form-data';
		$data[3]['fixture'] = 'Form element\n\r    Elements:\n\rPHPUnit is...fun!\n\r    Attributes: method=post, action=/service, enctype=multipart/form-data';
		$data[4]['fixture'] = 'Form element\n\r    Attributes: class=test, method=post, action=/service, enctype=multipart/form-data';
		$data[5]['fixture'] = 'Form element\n\r    Content: 1\n\r    Attributes: method=post, action=/service, enctype=multipart/form-data';
		$data[6]['fixture'] = 'Form element\n\r    ID: 1\n\r    Attributes: method=post, action=/service, enctype=multipart/form-data';

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
		$data[0]['fixture'] = '<form method="post" action="/service" enctype="multipart/form-data"></form>';
		$data[1]['fixture'] = '<form method="post" action="/service" enctype="multipart/form-data">Test</form>';
		$data[2]['fixture'] = '<form id="test" method="post" action="/service" enctype="multipart/form-data"></form>';
		$data[3]['fixture'] = '<form method="post" action="/service" enctype="multipart/form-data">PHPUnit is...fun!</form>';
		$data[4]['fixture'] = '<form class="test" method="post" action="/service" enctype="multipart/form-data"></form>';
		$data[5]['fixture'] = '<form method="post" action="/service" enctype="multipart/form-data">1</form>';
		$data[6]['fixture'] = '<form id="1" method="post" action="/service" enctype="multipart/form-data"></form>';

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
				'attributes'	=>	array('method' => 'post', 'action' => '/service', 'enctype' => 'multipart/form-data')
			),
			array(
				'content'		=>	'Test',
				'id'			=>	null,
				'elements'		=>	array(),
				'attributes'	=>	array('method' => 'post', 'action' => '/service', 'enctype' => 'multipart/form-data')
			),
			array(
				'content'		=>	null,
				'id'			=>	'test',
				'elements'		=>	array(),
				'attributes'	=>	array('method' => 'post', 'action' => '/service', 'enctype' => 'multipart/form-data')
			),
			array(
				'content'		=>	null,
				'id'			=>	null,
				'elements'		=>	array('PHPUnit is...', 'fun!'),
				'attributes'	=>	array('method' => 'post', 'action' => '/service', 'enctype' => 'multipart/form-data')
			),
			array(
				'content'		=>	null,
				'id'			=>	null,
				'elements'		=>	array(),
				'attributes'	=>	array('class' => 'test', 'method' => 'post', 'action' => '/service', 'enctype' => 'multipart/form-data')
			),
			array(
				'content'		=>	1,
				'id'			=>	null,
				'elements'		=>	array(),
				'attributes'	=>	array('method' => 'post', 'action' => '/service', 'enctype' => 'multipart/form-data')
			),
			array(
				'content'		=>	null,
				'id'			=>	1,
				'elements'		=>	array(),
				'attributes'	=>	array('method' => 'post', 'action' => '/service', 'enctype' => 'multipart/form-data')
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
				'valid'   => true
			),
			array(
				'element' => 'Fieldset',
				'valid'   => true
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
				'valid'   => true
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
				'valid'   => true,
				'params'  => [1]
			),
			array(
				'element' => 'HorizontalRule',
				'valid'   => true
			),
			array(
				'element' => 'Image',
				'valid'   => true
			),
			array(
				'element' => 'Input',
				'valid'   => true
			),
			array(
				'element' => 'Label',
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
				'valid'   => true
			),
			array(
				'element' => 'PageBreak',
				'valid'   => false
			),
			array(
				'element' => 'Paragraph',
				'valid'   => true
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
				'valid'   => true
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
				'valid'   => true
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
				'valid'   => true
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