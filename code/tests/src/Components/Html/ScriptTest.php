<?php
namespace Tests\Unit\Components\Html;

use N8G\Grass\Components\Html\Script;
use N8G\Utils\Log;

class ScriptTest extends \PHPUnit_Framework_TestCase
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
	 * @param  mixed $attributes The value for attributes from the data provider
	 * @return void
	 */
	public function testInstantiation($content, $attributes)
	{
		//No arguments
		$element = new Script();
		$this->assertInstanceOf('N8G\Grass\Components\Html\Script', $element);

		//Just content
		$element = new Script($content);
		$this->assertInstanceOf('N8G\Grass\Components\Html\Script', $element);

		//Content and attributes
		$element = new Script($content, $attributes);
		$this->assertInstanceOf('N8G\Grass\Components\Html\Script', $element);
	}

	/**
	 * Tests that id there is an invalid parameter passed on instantiation, there is a valid error thrown.
	 *
	 * @test
	 * @dataProvider invalidInstantiationProvider
	 * @expectedException PHPUnit_Framework_Error
	 *
	 * @param  mixed $content    The value for content from the data provider
	 * @param  mixed $attributes The value for attributes from the data provider
	 * @return void
	 */
	public function testInvalidInstantiation($content, $attributes)
	{
		$element = new Script($content, $attributes);
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
		$element = new Script();

		$element->setContent('This is a test');
		$this->assertEquals('This is a test', $element->getContent());
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
		$element = new Script();
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
		$element = new Script('This is a test.', array('class' => 'test'));
		$this->assertEquals(array('class' => 'test', 'type' => 'text/javascript'), $element->getAttributes());

		$element->setAttributes(array('style' => 'text-align: center;'));
		$this->assertEquals(array('style' => 'text-align: center;'), $element->getAttributes());
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
	public function testToString($content, $attributes, $fixture)
	{
		//Default element
		$element = new Script($content, $attributes);
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
	public function testToHtml($content, $attributes, $fixture)
	{
		$element = new Script($content, $attributes);
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
		$element = new Script();

		//Add valid attribute
		$element->addAttribute(array('class' => 'test'));
		$this->assertEquals(array('class' => 'test', 'type' => 'text/javascript'), $this->getPrivateProperty($element, 'attributes'));

		//Add second valid attribute
		$element->addAttribute(array('style' => 'text-align: center;'));
		$this->assertEquals(array('class' => 'test', 'type' => 'text/javascript', 'style' => 'text-align: center;'), $this->getPrivateProperty($element, 'attributes'));

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
		$data[0]['fixture'] = 'Script element\n\r    Attributes: type=text/javascript';
		$data[1]['fixture'] = 'Script element\n\r    Content: Test\n\r    Attributes: type=text/javascript';
		$data[2]['fixture'] = 'Script element\n\r    Attributes: type=text/javascript';
		$data[3]['fixture'] = 'Script element\n\r    Attributes: type=text/javascript';
		$data[4]['fixture'] = 'Script element\n\r    Attributes: class=test, type=text/javascript';
		$data[5]['fixture'] = 'Script element\n\r    Content: 1\n\r    Attributes: type=text/javascript';
		$data[6]['fixture'] = 'Script element\n\r    Attributes: type=text/javascript';

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
		$data[0]['fixture'] = '<script type="text/javascript"></script>';
		$data[1]['fixture'] = '<script type="text/javascript">Test</script>';
		$data[2]['fixture'] = '<script type="text/javascript"></script>';
		$data[3]['fixture'] = '<script type="text/javascript"></script>';
		$data[4]['fixture'] = '<script class="test" type="text/javascript"></script>';
		$data[5]['fixture'] = '<script type="text/javascript">1</script>';
		$data[6]['fixture'] = '<script type="text/javascript"></script>';

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
				'attributes'	=>	array()
			),
			array(
				'content'		=>	'Test',
				'attributes'	=>	array()
			),
			array(
				'content'		=>	null,
				'attributes'	=>	array()
			),
			array(
				'content'		=>	null,
				'attributes'	=>	array()
			),
			array(
				'content'		=>	null,
				'attributes'	=>	array('class' => 'test')
			),
			array(
				'content'		=>	1,
				'attributes'	=>	array()
			),
			array(
				'content'		=>	null,
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
				'attributes'	=>	'test'
			),
			array(
				'content'		=>	null,
				'attributes'	=>	1
			),
			array(
				'content'		=>	null,
				'attributes'	=>	false
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