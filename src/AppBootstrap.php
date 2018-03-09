<?php

namespace Phlalette\Bootstrap;

use App\Library\Environment;
use App\Library\Logging\MultiLogger;
use App\Library\Logging\PhalconLogger;
use App\Library\Logging\TracyLogger;
use App\Library\Plugin\Json\ResponsePlugin;
use App\Modules\V1\Routing\RouterV1;
use Phalcon\Config as PhConfig;
use Phalcon\Db\Adapter\Pdo\Mysql as PhDatabaseConnection;
use Phalcon\Debug as PhDebug;
use Phalcon\Di as PhDi;
use Phalcon\Di\FactoryDefault as PhDiFactoryDefault;
use Phalcon\Events\Event as PhEvent;
use Phalcon\Http\Response as PhResponse;
use Phalcon\Logger as PhLogger;
use Phalcon\Logger\Adapter\File as PhFileLogger;
use Phalcon\Mvc\Dispatcher as PhDispatcher;
use Phalcon\Mvc\Micro as PhMicro;
use Phalcon\Mvc\Router as PhRouter;
use Phalcon\Mvc\Url as PhUrl;
use Phalcon\Session\Adapter\Files as PhFileSession;
use Tracy\Debugger;

final class AppBootstrap extends Bootstrap
{


	/**
	 * API *********************************************************************
	 */

	/**
	 * @param string|null $di
	 */
	public function run($di = NULL)
	{

	}

	/**
	 * @param string|null $uri
	 */
	public function dispatch($uri = NULL)
	{

	}

}

