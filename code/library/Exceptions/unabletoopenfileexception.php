<?php
namespace Exceptions\Components;

use Exceptions\ExceptionAbstract;

class UnableToOpenFileException extends ExceptionAbstract
{
	public function __construct($message, $code = -1, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}