<?php
namespace Utils;

/**
 * This function acts as a logging mechinism. This can be used in many situations
 * and on different sites. This is a static class and can be called from anywhere.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
class Log
{
	//Log categoies
	const FATAL = 0;
	const ERROR = 1;
	const WARN = 2;
	const NOTICE = 3;
	const INFO = 4;
	const DEBUG = 5;

	/**
	 * The spacing needed to format the log file
	 */
	const LOG_SPACING = PHP_EOL;

	/**
	 * Instance of this class
	 * @var object
	 */
	private static $instance;

	/**
	 * The file to be written to
	 * @var pointer
	 */
	private static $file;

	private function __construct($directory, $filename)
	{
		//set the name of the file
		self::$file = $this->getFile($directory, $filename);
	}

	public function __destruct()
	{
		if (self::$file !== null) {
			fclose(self::$file);
		}
	}

	public static function init($directory = 'Logs/', $filename = NULL)
	{
		$directory = $directory;
		//Check for instance of the class
		if (self::$instance === null) {
			self::$instance = new self($directory, $filename);
		}
	}

	private static function getFile($directory, $filename)
	{
		//Create file name if there is none
		if ($filename === NULL) {
			$filename = date('Y-m-d') . '.log';
		}

		//Check the director exists
		if (!is_dir($directory)) {
			mkdir($directory, 0777, true);
		}

		//Check the files exists
		if (false === self::$file = fopen($directory . $filename, 'a')) {
			throw new Exception('Could not create log file!');
		}
		return self::$file;
	}

	private static function writeToFile($cat, $msg)
	{
		//check if the class has been initiated
		self::init();

		$message = sprintf(
			'{%s} %s - %s%s',
			date('d\/m\/Y H:i:s'),
			self::getCategory($cat),
			$msg,
			PHP_EOL
		);
		fwrite(self::$file, $message);
	}

	private static function getCategory($cat)
	{
		switch ($cat) {
			case self::FATAL :
				// $colour = '0;31';
				$category = 'FATAL  ';
				break;
			case self::ERROR :
				// $colour = '1;33';
				$category = 'ERROR  ';
				break;
			case self::WARN :
				// $colour = '0;35';
				$category = 'WARNING';
				break;
			case self::NOTICE :
				// $colour = '0;36';
				$category = 'NOTICE ';
				break;
			case self::INFO :
				// $colour = '0;34';
				$category = 'INFO   ';
				break;
			case self::DEBUG :
				// $colour = '0;32';
				$category = 'DEBUG  ';
				break;
		}
		// return sprintf("\033[%sm %s \033[0m ", $colour, $category);
		return $category;
	}

	public static function fatal($msg)
	{
		self::writeToFile(self::FATAL, $msg);
	}

	public static function error($msg)
	{
		self::writeToFile(self::ERROR, $msg);
	}

	public static function warn($msg)
	{
		self::writeToFile(self::WARN, $msg);
	}

	public static function notice($msg)
	{
		self::writeToFile(self::NOTICE, $msg);
	}

	public static function info($msg)
	{
		self::writeToFile(self::INFO, $msg);
	}

	public static function debug($msg)
	{
		self::writeToFile(self::DEBUG, $msg);
	}
}