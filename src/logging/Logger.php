<?php

namespace Phalette\Bootstrap\Logging;

use Exception;

interface Logger
{

	/**
	 * @param Exception $e
	 */
	public function exception(Exception $e);

}
