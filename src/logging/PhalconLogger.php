<?php

namespace Phalette\Bootstrap\Logging;

use Exception;
use Phalcon\Logger\Adapter as PhLogger;

final class PhalconLogger implements Logger
{

	/** @var PhLogger */
	private $logger;

	/**
	 * @param PhLogger $logger
	 */
	function __construct(PhLogger $logger)
	{
		$this->logger = $logger;
	}

	/**
	 * @param Exception $e
	 */
	public function exception(Exception $e)
	{
		$message = get_class($e) . ": " . $e->getMessage() . "\n"
			. " File=" . $e->getFile() . "\n"
			. " Line=" . $e->getLine() . "\n"
			. $e->getTraceAsString() . "\n";

		$this->logger->critical($message);
	}

}
