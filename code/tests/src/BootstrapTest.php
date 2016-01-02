<?php
namespace N8G\Grass;

use N8G\Grass\Bootstrap;

class BootstrapTest extends \PHPUnit_Framework_TestCase
{
	//Set up and tear down

	/**
     * Cleans up after a test
     */
    public static function tearDownAfterClass()
    {
		// exec('[ -d "tests/fixtures/logs/" ] && rm tests/fixtures/logs/*');
    }

	//Tests

	/**
	 * [test description]
	 *
	 * @test
	 *
	 * @return [type] [description]
	 */
	public function testRun()
	{
		$bootstrap = new Bootstrap();
		$bootstrap->run('./tests/fixtures/logs/', 'testsLog.log');

		$this->assertFileExists('./tests/fixtures/logs/testsLog.log');
	}

	/**
	 * [test description]
	 *
	 * @test
	 * @depends testRun
	 *
	 * @return [type] [description]
	 */
	public function testTearDown()
	{}

	/**
	 * [test description]
	 *
	 * @test
	 * @depends testRun
	 *
	 * @return [type] [description]
	 */
	public function testCheckSiteSetup()
	{}

	/**
	 * [test description]
	 *
	 * @test
	 * @dataProvider errorProvider
	 * @depends testRun
	 *
	 * @return [type] [description]
	 */
	public function testErrorHandler($enum, $code, $type, $method, $message, $file, $line, $context)
	{
		$bootstrap = new Bootstrap();

		//Call the function
		$error = $bootstrap->errorHandler($code, $message, $file, $line, $context);
	}

	/**
	 * [test description]
	 *
	 * @test
	 * @dataProvider errorProvider
	 *
	 * @return [type] [description]
	 */
	public function testGetMethod($enum, $code, $type, $method)
	{
		$actual = $this->invokeMethod(new Bootstrap, 'getMethod', array('code' => $enum));

		//Check the enumeration and the code are as expected
		$this->assertEquals($method, $actual);
	}

	/**
	 * [test description]
	 *
	 * @test
	 * @dataProvider errorProvider
	 *
	 * @return void
	 */
	public function testGetType($enum, $code, $type)
	{
		$actual = $this->invokeMethod(new Bootstrap, 'getType', array('code' => $enum));

		//Check the enumeration and the code are as expected
		if ($enum !== 0)
			$this->assertEquals($code, $enum);
		$this->assertEquals($type, $actual);
	}

	//Data providers

	public function errorProvider()
	{
		return array(
			array(
				'enum'     => E_ERROR,
				'code'     => 1,
            	'type'     => 'ERROR',
            	'method'   => 'error',
            	'message'  => 'Error handler test!',
            	'file'     => 'tests/src/BootstrapTests.php',
            	'line'     => 1,
            	'context'  => null
            ),
            array(
            	'enum'     => E_WARNING,
            	'code'     => 2,
            	'type'     => 'WARNING',
            	'method'   => 'warn',
            	'message'  => 'Error handler test!',
            	'file'     => 'tests/src/BootstrapTests.php',
            	'line'     => 1,
            	'context'  => null
            ),
            array(
            	'enum'     => E_PARSE,
            	'code'     => 4,
            	'type'     => 'PARSE',
            	'method'   => 'fatal',
            	'message'  => 'Error handler test!',
            	'file'     => 'tests/src/BootstrapTests.php',
            	'line'     => 1,
            	'context'  => null
            ),
            array(
            	'enum'     => E_NOTICE,
            	'code'     => 8,
            	'type'     => 'NOTICE',
            	'method'   => 'warn',
            	'message'  => 'Error handler test!',
            	'file'     => 'tests/src/BootstrapTests.php',
            	'line'     => 1,
            	'context'  => null
            ),
            array(
            	'enum'     => E_CORE_ERROR,
            	'code'     => 16,
            	'type'     => 'CORE ERROR',
            	'method'   => 'fatal',
            	'message'  => 'Error handler test!',
            	'file'     => 'tests/src/BootstrapTests.php',
            	'line'     => 1,
            	'context'  => null
            ),
            array(
            	'enum'     => E_CORE_WARNING,
            	'code'     => 32,
            	'type'     => 'CORE WARNING',
            	'method'   => 'warn',
            	'message'  => 'Error handler test!',
            	'file'     => 'tests/src/BootstrapTests.php',
            	'line'     => 1,
            	'context'  => null
            ),
            array(
            	'enum'     => E_COMPILE_ERROR,
            	'code'     => 64,
            	'type'     => 'COMPILE ERROR',
            	'method'   => 'fatal',
            	'message'  => 'Error handler test!',
            	'file'     => 'tests/src/BootstrapTests.php',
            	'line'     => 1,
            	'context'  => null
            ),
            array(
            	'enum'     => E_COMPILE_WARNING,
            	'code'     => 128,
            	'type'     => 'COMPILE WARNING',
            	'method'   => 'warn',
            	'message'  => 'Error handler test!',
            	'file'     => 'tests/src/BootstrapTests.php',
            	'line'     => 1,
            	'context'  => null
            ),
            array(
            	'enum'     => E_USER_ERROR,
            	'code'     => 256,
            	'type'     => 'USER ERROR',
            	'method'   => 'error',
            	'message'  => 'Error handler test!',
            	'file'     => 'tests/src/BootstrapTests.php',
            	'line'     => 1,
            	'context'  => null
            ),
            array(
            	'enum'     => E_USER_WARNING,
            	'code'     => 512,
            	'type'     => 'USER WARNING',
            	'method'   => 'warn',
            	'message'  => 'Error handler test!',
            	'file'     => 'tests/src/BootstrapTests.php',
            	'line'     => 1,
            	'context'  => null
            ),
            array(
            	'enum'     => E_USER_NOTICE,
            	'code'     => 1024,
            	'type'     => 'USER NOTICE',
            	'method'   => 'warn',
            	'message'  => 'Error handler test!',
            	'file'     => 'tests/src/BootstrapTests.php',
            	'line'     => 1,
            	'context'  => null
            ),
            array(
            	'enum'     => E_STRICT,
            	'code'     => 2048,
            	'type'     => 'STRICT',
            	'method'   => 'warn',
            	'message'  => 'Error handler test!',
            	'file'     => 'tests/src/BootstrapTests.php',
            	'line'     => 1,
            	'context'  => null
            ),
            array(
            	'enum'     => E_RECOVERABLE_ERROR,
            	'code'     => 4096,
            	'type'     => 'RECOVERABLE',
            	'method'   => 'error',
            	'message'  => 'Error handler test!',
            	'file'     => 'tests/src/BootstrapTests.php',
            	'line'     => 1,
            	'context'  => null
            ),
            array(
            	'enum'     => E_DEPRECATED,
            	'code'     => 8192,
            	'type'     => 'DEPRECATED',
            	'method'   => 'error',
            	'message'  => 'Error handler test!',
            	'file'     => 'tests/src/BootstrapTests.php',
            	'line'     => 1,
            	'context'  => null
            ),
            array(
            	'enum'     => E_USER_DEPRECATED,
            	'code'     => 16384,
            	'type'     => 'USER DEPRECATED',
            	'method'   => 'error',
            	'message'  => 'Error handler test!',
            	'file'     => 'tests/src/BootstrapTests.php',
            	'line'     => 1,
            	'context'  => null
            ),
            array(
            	'enum'     => 0,
            	'code'     => 'default',
            	'type'     => 'UNKNOWN (0)',
            	'method'   => 'error',
            	'message'  => 'Error handler test!',
            	'file'     => 'tests/src/BootstrapTests.php',
            	'line'     => 1,
            	'context'  => null
			)
		);
	}

	//Mock Objects



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
}