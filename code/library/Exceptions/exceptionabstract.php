<?php
namespace Exceptions;

use Utils\Log;

abstract class ExceptionAbstract extends \Exception
{
	public function __construct($message, $code = -1, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

	protected function log()
	{
		//Write to log
		$message = sprintf('Exception Thrown:  %s%s        Message:  %s%s        File:     %s%s        Line:     %s%s        Trace:    %s',
							str_replace('Exceptions\\', '', get_class($this)),
							Log::LOG_SPACING,
							$this->getMessage(),
							Log::LOG_SPACING,
							$this->getFile(),
							Log::LOG_SPACING,
							$this->getLine(),
							Log::LOG_SPACING,
							$this->getTraceAsString());

		switch ($this->getCode()) {
			case Log::FATAL :
				Log::fatal($message);
				break;
			case Log::ERROR :
				Log::error($message);
				break;
			case Log::WARN :
				Log::warn($message);
				break;
			case Log::NOTICE :
				Log::notice($message);
				break;
			case Log::INFO :
				Log::info($message);
				break;
			case Log::DEBUG :
				Log::debug($message);
				break;
		}
	}
}