<?php

namespace Phalette\Bootstrap\Logging;

use Exception;

final class MultiLogger implements Logger
{

	/** @var Logger[] */
	private $loggers = [];

	/**
	 * @param Logger $logger
	 */
	public function add(Logger $logger)
	{
		$this->loggers[] = $logger;
	}

	/**
	 * @param Exception $e
	 */
	public function exception(Exception $e)
	{
		foreach ($this->loggers as $l) {
			$l->exception($e);
		}
	}

}
