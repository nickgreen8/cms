<?php
namespace Tests\Unit\Components\Html;

use N8G\Grass\Components\Html\HtmlAbstract;
use N8G\Utils\Log;

class HtmlAbstractTest extends \PHPUnit_Framework_TestCase
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
	 *
	 * @param  mixed $content    The value for content from the data provider
	 * @param  mixed $id         The value for id from the data provider
	 * @param  mixed $elements   The value for elements from the data provider
	 * @param  mixed $attributes The value for attributes from the data provider
	 * @return void
	 */
	public function testInstantiation($content, $id, $elements, $attributes)
	{
		
	}

	/**
	 * Tests that id there is an invalid parameter passed on instantiation, there is a valid error thrown.
	 *
	 * @test
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
		
	}

	/**
	 * Tests the string return for the component.
	 *
	 * @test
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
		
	}

	/**
	 * Tests that the class builds the HTML correctly.
	 *
	 * @test
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
		
	}

	/**
	 * Ensures that the ID can be got successfully.
	 *
	 * @test
	 *
	 * @return void
	 */
	public function testGetId()
	{
		
	}

	/**
	 * Checks that you can get the elements within the class.
	 *
	 * @test
	 *
	 * @param mixed $elements The element to be added to the component
	 * @param array $expected The array that is expected to have been set
	 *
	 * @return void
	 */
	public function testGetElements($elements, $expected)
	{

	}

	/**
	 * Tests that content can be got on the object.
	 *
	 * @test
	 *
	 * @return void
	 */
	public function testGetContent()
	{
		
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
		
	}

	/**
	 * Checks that you can set the elements within the class.
	 *
	 * @test
	 *
	 * @param mixed $elements The element to be added to the component
	 * @param array $expected The array that is expected to have been set
	 *
	 * @return void
	 */
	public function testSetElements($elements, $expected)
	{
		
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

	}

	/**
	 * This is the test for adding elements. Some of the elements will be added but some will be invalid and they will
	 * not be added.
	 *
	 * @test
	 *
	 * @param mixed   $element The element to be added to the component
	 * @param boolean $valid   Whether the element is valid
	 * @param array   $params  Any params that need passing to the child
	 * @return void
	 */
	public function testAddElements($element, $valid, $params = null)
	{

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
		
	}

	// Data providers



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