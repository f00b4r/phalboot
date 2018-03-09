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

final class Bootstrap
{

	/** @var PhMicro */
	protected $app;

	/** @var PhDi */
	protected $di;

	/** @var string */
	protected $mode;

	/**
	 * @param string $mode
	 */
	function __construct($mode = Environment::DEVELOPMENT)
	{
		$this->mode = $mode;
	}

	/**
	 * API *********************************************************************
	 */

	/**
	 * @param null|string $di
	 */
	public function run($di = NULL)
	{
		// Create APP
		$this->app = new PhMicro();

		// Create DI

		if ($di === NULL) {
			$this->di = new PhDi();
		} else if ($di === 'auto') {
			$this->di = new PhDiFactoryDefault();
		} else {
			$this->di = $di;
		}

		$this->app->setDI($this->di);

		// Init services

		$this->initServices();
	}

	/**
	 * @param string|null $uri
	 */
	public function dispatch($uri = NULL)
	{
		try {
			// Handle request
			$this->app->handle($uri);
		} catch (\Exception $e) {

			// Log the exception
			$logger = $this->di->get('logger');
			$logger->exception($e);

			if ($this->isDebug()) {
				// Show error via Tracy
				Debugger::exceptionHandler($e, TRUE);
			} else {
				// Show an static error page
				$response = new PhResponse();
				$response->redirect('505.html');
				$response->send();
			}
		}
	}

	/**
	 * FACTORIES ***************************************************************
	 * *************************************************************************
	 */

	protected function initConfig()
	{
		$this->di->setShared('config', function () {
			return require_once __DIR__ . '/config/config.local.php';
		});
	}

	protected function initRouting()
	{
		RouterV1::mount($this->app);
	}

	protected function initLogging()
	{
		Debugger::enable($this->isDebug() ? Debugger::DEVELOPMENT : Debugger::PRODUCTION, APP_PATH . '/app/logs');
		Debugger::$strictMode = TRUE;
		Debugger::$scream = TRUE;
	}

	protected function initServices()
	{
		// Auth
		$this->di->setShared('auth', 'App\Library\Plugin\Auth\TokenAuthPlugin');

		// Http
		$this->di->setShared('router', 'Phalcon\Mvc\Router');
		$this->di->setShared('request', 'Phalcon\Http\Request');
		$this->di->setShared('response', 'Phalcon\Http\Response');

		// Events
		$this->di->setShared('eventsManager', [
			'className' => 'Phalcon\Events\Manager',
			'calls' => [
				['method' => 'attach', 'arguments' => [
					['type' => 'parameter', 'value' => 'micro:beforeHandleRoute'],
					['type' => 'service', 'name' => 'auth']
				]]
			]
		]);

		// Logging
		$this->di->setShared('logger', function () {
			$multi = new MultiLogger();
			$multi->add(new PhalconLogger(new PhFileLogger($this->di->get('config')->application->logsDir . '/' . $this->mode . '.log')));
			$multi->add(new TracyLogger());
			return $multi;
		});

		// Model
		$this->di->setShared('modelsManager', 'Phalcon\Mvc\Model\Manager');
		$this->di->setShared('modelsMetadata', [
			'className' => 'Phalcon\Mvc\Model\MetaData\Files',
			'arguments' => [
				['type' => 'parameter', 'value' => ['metaDataDir' => APP_PATH . '/app/cache/metadata/']]
			],
		]);
		$this->di->setShared('collectionManager', 'Phalcon\Mvc\Collection\Manager');
		$this->di->setShared('repositoryManager', 'App\Model\Orm\RepositoryManager');

		// =====================================================================

		/**
		 * Database connection is created based in the parameters defined in the configuration file
		 */
		$this->di->setShared('db', function () {
			$connection = new PhDatabaseConnection($this->di->get('config')->db->mysql->toArray());
			if ($this->isDebug()) {
				$em = $this->di->get('eventsManager');
				$logger = new PhFileLogger($this->di->get('config')->application->logsDir . "/db.log");
				//Listen all the database events
				$em->attach(
					'db',
					function (PhEvent $event, PhDatabaseConnection $connection) use ($logger) {
						if ($event->getType() == 'beforeQuery') {
							/** @var PhDatabaseConnection $connection */
							$variables = $connection->getSQLVariables();
							if ($variables) {
								$logger->log($connection->getSQLStatement() . ' [' . join(',', $variables) . ']', PhLogger::INFO);
							} else {
								$logger->log($connection->getSQLStatement(), PhLogger::INFO);
							}
						}
					}
				);

				//Assign the eventsManager to the db adapter instance
				$connection->setEventsManager($em);
			}
			return $connection;
		});

		/**
		 * URL
		 */
		$this->di->setShared('url', function () {
			$url = new PhUrl();

			if ($this->di->get('config')->application->baseUri) {
				$url->setBaseUri($this->config->application->baseUri);
			}
			if ($this->di->get('config')->application->basePath) {
				$url->setBasePath($this->config->application->basePath);
			}

			return $url;
		});

		/**
		 * Session
		 */
		$this->di->setShared('session', function () {
			$session = new PhFileSession();
			$session->start();
			return $session;
		});
	}

	/**
	 * HELPERS *****************************************************************
	 * *************************************************************************
	 */

	/**
	 * @return bool
	 */
	protected function isDebug()
	{
		return $this->mode === Environment::DEVELOPMENT;
	}

}

