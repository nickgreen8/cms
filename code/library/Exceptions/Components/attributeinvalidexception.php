<?php
namespace Exceptions\Components;

use Exceptions\ExceptionAbstract,
	Utils\Log;

/**
 * This exception is thown when a component has an invalid attribute.
 *
 * @author Nick Green <nick-green@live.co.uk>
 */
class AttributeInvalidException extends ExceptionAbstract
{
	/**
	 * Default custom exception constructor
	 *
	 * @param string         $message  A message to embed in the exception
	 * @param integer        $code     A user defined error
	 * @param Exception|null $previous A previous exception that has caused this exception
	 */
	public function __construct($message, $code = Log::ERROR, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
		$this->log();
	}
}