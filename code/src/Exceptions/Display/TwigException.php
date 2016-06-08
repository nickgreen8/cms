<?php
namespace N8G\Grass\Exceptions\Display;

/**
 * This exception is thown when a component has an invalid attribute.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
class TwigException extends \Exception
{
	/**
	 * Default custom exception constructor
	 *
	 * @param string         $message  A message to embed in the exception
	 * @param integer        $code     A user defined error
	 * @param Exception|null $previous A previous exception that has caused this exception
	 */
	public function __construct($message, $code = 500, Exception $previous = null)
	{
	}
}