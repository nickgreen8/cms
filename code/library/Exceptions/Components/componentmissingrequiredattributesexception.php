<?php
namespace Exceptions\Components;

use Exceptions\ExceptionAbstract;

class ComponentMissingRequiredAttributesException extends ExceptionAbstract
{
	public function __construct($message, $code = Log::ERROR, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
		$this->log();
	}
}