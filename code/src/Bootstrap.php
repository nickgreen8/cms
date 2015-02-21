<?php
namespace N8G\Cms;

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
	public static function run()
	{
		//Set PHP config
		error_reporting(E_ALL);
		date_default_timezone_set('Europe/London');

		//Initilise Logging
		Log::init(__DIR__ . '/../logs/');

		//Initilise error handler
		set_error_handler(array('Bootstrap', 'errorHandler'));

		//Initilise the DB connection
		$config = Json::readFile('./config/config.json');
		Database::init(
			$config->database->username,
			$config->database->password,
			$config->database->name,
			$config->database->type,
			$config->database->host
		);

		//Check for site setup
		self::checkSiteSetup();

		//Specify new page is opening
		Log::custom(sprintf(
					'%s%s==========%sNew Page Loaded%s==========%s%s%s%s%s',
					PHP_EOL, PHP_EOL, PHP_EOL, PHP_EOL, PHP_EOL,
					date('l jS F Y'), PHP_EOL, date('H:ia'), PHP_EOL));

		//Initilise the config container
		Config::init();

		//Populate the config container
		$query = Database::perform('Config', array('*'), 'select');
		while ($row = Database::getArray($query)) {
			Config::setItem($row['name'], $row['value']);
		}
	}

	/**
	 * This function is used to close connections and stop any processes running in
	 * the background. Nothing is passed to this function and nothing is returned.
	 *
	 * @return void
	 */
	public static function tearDown()
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
	private static function checkSiteSetup()
	{}

	/**
	 * This function takes any errors, processes them and will cause the application
	 * to react as required.
	 * @return void
	 */
	public static function errorHandler(int $errno, $errstr, $errfile, int $errline, array $errcontext)
	{
		var_dump($errno, $errstr, $errfile, $errline, $errcontext);

		/*if (!(error_reporting() & $errno)) {
	        // This error code is not included in error_reporting
	        return;
	    }

	    switch ($errno) {
	    case E_USER_ERROR:
	        break;

	    case E_USER_WARNING:
	        break;

	    case E_USER_NOTICE:
	        break;

	    default:
	        break;
	    }

	    /* Don't execute PHP internal error handler */
	    //return true;
	}
}