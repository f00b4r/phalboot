<?php

namespace Phalette\Bootstrap\Logging;

use Exception;
use Tracy\Debugger;

final class TracyLogger implements Logger
{

	/**
	 * @param Exception $e
	 */
	public function exception(Exception $e)
	{
		Debugger::log($e, Debugger::CRITICAL);
	}

}
