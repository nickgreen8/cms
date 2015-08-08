<?php
namespace N8G\Grass;

use N8G\Database\Database,
	N8G\Utils\Log,
	N8G\Utils\Json,
	N8G\Utils\Config;

/**
 * This class is use to initilise the processes and connections that will be needed on
 * an initial page load. Processes will also be stoped at the end of a page load using
 * the functions in this class.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
class Bootstrap
{
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
		Log::init(__DIR__ . '/../logs/');

		//Initilise error handler
		set_error_handler(function($errno, $errstr, $errfile, $errline, $errcontext) { $this->errorHandler($errno, $errstr, $errfile, $errline, $errcontext); });

		//Specify new page is opening
		Log::custom(sprintf(
					'%s%s===========================%sNew Page Loaded%s===========================%s%s%s%s%s',
					PHP_EOL, PHP_EOL, PHP_EOL, PHP_EOL, PHP_EOL,
					date('l jS F Y'), PHP_EOL, date('H:ia'), PHP_EOL));

		//Initilise the DB connection
		$config = Json::readFile('./config/config.json');
		Database::init(
			$config->database->username,
			$config->database->password,
			$config->database->name,
			$config->database->type,
			$config->database->host
		);
		Database::setPrefix($config->database->prefix);

		//Check for site setup
		$this->checkSiteSetup();

		//Initilise the config container
		Config::init();

		//Populate the config container
		$query = Database::perform('Config', array('*'), 'select');
		while ($row = Database::getArray($query)) {
			Config::setItem($row['name'], $row['value']);
		}

		//Add current page to config
		Config::setItem('page-id', isset($_REQUEST['id']) ? $_REQUEST['id'] : 1);
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
		Database::close();

		//Calculate and output the load time
		$time = round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 5);
		Log::success(sprintf('Page load took %f seconds', $time));
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
		Log::$method($log);

	    //Don't execute PHP's own error handler
	    return true;
	}

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