<?php
namespace N8G\Grass;

use N8G\Database\Database;
use N8G\Utils\Log;
use N8G\Utils\Json;
use N8G\Utils\Config;
use N8G\Grass\Display\Output;
use N8G\Grass\Display\Twig;
use N8G\Grass\Display\Theme;
use N8G\Grass\Display\Navigation;

/**
 * This class is use to initilise the processes and connections that will be needed on
 * an initial page load. Processes will also be stoped at the end of a page load using
 * the functions in this class.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
class Container
{
	/**
	 * 
	 * @var array
	 */
	private $elements = array();

	/**
	 * This function is the initial call that is will begin any processes and will
	 * create connections that will be required for a page to be created. Nothing
	 * is passed to this function and nothing is returned.
	 *
	 * @return void
	 */
	public function run()
	{
		//Set PHP config
		error_reporting(E_ALL);
		date_default_timezone_set('Europe/London');

		//Initilise Logging
		$this->elements['log'] = new Log;
		$this->elements['log']->init(sprintf('%s/../logs/', __DIR__));

		//Initilise the DB connection
		$this->elements['json']     = new Json($this);
		$this->elements['config']   = $this->elements['json']->readFile('./config/config.json', true);
		$this->elements['database'] = Database::init(
			$this->elements['config']['database']['username'],
			$this->elements['config']['database']['password'],
			$this->elements['config']['database']['name'],
			$this->elements['config']['database']['type'],
			$this->elements['config']['database']['host']
		);

		//Populate the config container
		$query = $this->elements['database']->query('Config', array('*'), 'select');
		while ($row = $this->elements['database']->getArray($query)) {
			$this->elements['config'][$row['name']] = $row['value'];
		}

		//Add current page to config
		$this->elements['config']['page-id'] = isset($_REQUEST['id']) ? $_REQUEST['id'] : 1;

		//Define blog filter types
		$this->elements['config']['blogFilters'] = array('month');

		//Create funtional classes
		$this->elements['output']     = new Output($this);
		$this->elements['twig']       = new Twig($this);
		$this->elements['theme']      = new Theme($this);
		$this->elements['navigation'] = new Navigation($this);

		//Initilise error handler
		set_error_handler(function($errno, $errstr, $errfile, $errline, $errcontext) { $this->errorHandler($errno, $errstr, $errfile, $errline, $errcontext); });

		//Check for site setup
		$this->checkSiteSetup();
	}

	/**
	 * Adds a new element to the container.
	 *
	 * @param  string $key     The key of the new element.
	 * @param  mixed  $element The element to be added to the container.
	 * @return void
	 */
	public function add($key, $element)
	{
		$this->elements[$key] = $element;
	}

	/**
	 * Gets an element from the container.
	 *
	 * @param  string $key The key of the element to be returned.
	 * @return mixed       The element.
	 */
	public function get($key)
	{
		return $this->elements[$key];
	}

	/**
	 * This function is used to close connections and stop any processes running in
	 * the background. Nothing is passed to this function and nothing is returned.
	 *
	 * @return void
	 */
	public function tearDown()
	{
		//Close the connection to the DB
		$this->elements['database']->close();

		//Calculate and output the load time
		$time = round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 5);
		$this->get('log')->success(sprintf('Page load took %f seconds', $time));
	}

	/**
	 * This function takes any errors, processes them and will cause the application
	 * to react as required.
	 *
	 * @param  integer $code    The error code.
	 * @param  string  $message The error message.
	 * @param  string  $file    The filename where the error is thrown.
	 * @param  integer $line    The line number where the error is.
	 * @param  mixed   $context
	 * @return boolean          True.
	 */
	public function errorHandler($code, $message, $file, $line, $context)
	{
		//Get the relevant method for the log
		$method = $this->getMethod($code);

		//Build the log entry
		$log = sprintf(
			'[code: %d] [type: %s] [file: %s] [line: %d] %s',
			$code,
			$this->getType($code),
			$file,
			$line,
			$message
		);

		//Log the error
		$this->get('log')->$method($log);

	    //Don't execute PHP's own error handler
	    return true;
	}

	/**
	 * Checks that a key exists within the container.
	 *
	 * @param  string $key The key to check for
	 * @return boolean     Indicates whether the key exists or not
	 */
	private function keyExists($key)
	{
		if (in_array($key, $this->elements)) {
			return true;
		}
		return false;
	}

	/**
	 * This function is used to check that the site has been set up. If the setup is
	 * correct then the application will function as expected. If not, the
	 * application will behave differently.
	 * @return void
	 */
	private function checkSiteSetup()
	{}

	/**
	 * This function is used to get the relevent function to log the error with. The
	 * error code is passed and before the log method to be used is passed back as a
	 * string.
	 *
	 * @param  integer $code The PHP error code for the error that has been thrown
	 * @return string        The function to log the error with
	 */
	private function getMethod($code)
	{
		switch ($code) {
			case E_PARSE :
            case E_CORE_ERROR :
            case E_COMPILE_ERROR :
                return 'fatal';
                break;
            case E_WARNING :
            case E_CORE_WARNING :
            case E_COMPILE_WARNING :
            case E_USER_WARNING :
            case E_NOTICE :
            case E_USER_NOTICE :
            case E_STRICT :
                return 'warn';
                break;
            default :
                return 'error';
                break;
        }
	}

	/**
	 * This function gets the error type as a string so that it can be displayed in the
	 * logs. The error code is passed and before the type is passed back as a string.
	 *
	 * @param  integer $code The PHP error code for the error that has been thrown
	 * @return string        The error type as a string
	 */
	private function getType($code)
	{
		//Use the code to determine the error
		switch ($code) {
            case E_ERROR :				// 1
            	return 'ERROR';
            	break;
            case E_WARNING :			// 2
            	return 'WARNING';
            	break;
            case E_PARSE :				// 4
            	return 'PARSE';
            	break;
            case E_NOTICE :				// 8
            	return 'NOTICE';
            	break;
            case E_CORE_ERROR :			// 16
            	return 'CORE ERROR';
            	break;
            case E_CORE_WARNING :		// 32
            	return 'CORE WARNING';
            	break;
            case E_CORE_ERROR :			// 64
            	return 'CORE ERROR';
            	break;
            case E_CORE_WARNING :		// 128
            	return 'CORE WARNING';
            	break;
            case E_USER_ERROR :			// 256
            	return 'USER ERROR';
            	break;
            case E_USER_WARNING :		// 512
            	return 'USER WARNING';
            	break;
            case E_USER_NOTICE :		// 1024
            	return 'USER NOTICE';
            	break;
            case E_STRICT :				// 2048
            	return 'STRICT';
            	break;
            case E_RECOVERABLE_ERROR :	// 4096
            	return 'RECOVERABLE';
            	break;
            case E_DEPRECATED :			// 8192
            	return 'DEPRECATED';
            	break;
            case E_USER_DEPRECATED :	// 16384
            	return 'USER DEPRECATED';
            	break;
            default :					// Unknown
            	return sprintf('UNKNOWN (%d)', $code);
            	break;
        }
	}
}