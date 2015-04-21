<?php
namespace N8G\Grass\Exceptions\Components;

use N8G\Grass\Exceptions\ExceptionAbstract;

/**
 * This exception is thown when a class cannot be found or it does not exist.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
class ClassNotFoundException extends ExceptionAbstract
{
	/**
	 * Default custom exception constructor
	 *
	 * @param string         $message  A message to embed in the exception
	 * @param integer        $code     A user defined error
	 * @param Exception|null $previous A previous exception that has caused this exception
	 */
	public function __construct($message, $code = -1, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
		$this->log();
	}
}