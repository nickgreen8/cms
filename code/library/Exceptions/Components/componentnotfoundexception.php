<?php
namespace Exceptions\Components;

use Exceptions\ExceptionAbstract;

class ComponentNotFoundException extends ExceptionAbstract
{
	public function __construct($message, $code = -1, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
		$this->log();
	}
}