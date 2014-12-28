<?php
namespace Website;

/**
 * This class is used to create an autoload function for the complete library. This
 * will mean that there will only be a single include needed on the initial index page
 * instead of throughout the code.
 *
 * @author  Nick Green <nick-green@live.co.uk>
 */
final class Autoload
{
	/**
	 * Object of the singleton class
	 * @var Object
	 */
	private static $instance;

	/**
	 * Default constructor for the class
	 */
	private function __construct()
	{
		//Register the autoloader
		spl_autoload_register(array($this, 'autoload'));
	}

	/**
	 * This function is called to get the instance of itself. This means that there
	 * will only ever be one instance of this object instantiated at any one time. If
	 * the class has not already been created then it will be created. Nothing is
	 * passed to this function. The instance of the class is returned.
	 *
	 * @return Object The object of this class
	 */
	public static function getInstance()
	{
		//Check for instance existing
		if (self::$instance === null) {
			//Create new instance of class
			self::$instance = new self();
		}
		//Return the instance of the class
		return self::$instance;
	}

	/**
	 * Loads in the file of any class that is called.
	 *
	 * @param  string $class The name of the class to be loaded.
	 * @return void
	 */
	private function autoload($class)
	{
		$file = __DIR__ . '/';
		$path = explode('\\', $class);
		$path[count($path) - 1] = strtolower(end($path));
		switch ($class) {
			default :
				$file .= implode('/', $path) . '.php';
				break;
		}

		if (!file_exists($file)) {
			echo '<h1>ERROR!</h1>';
		}
		require_once $file;
	}
}