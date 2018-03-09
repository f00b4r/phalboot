<?php

namespace Phalette\Bootstrap\Routing;

use Phalcon\Mvc\Micro\Collection as PhCollection;

abstract class ModuleCollection extends PhCollection
{

	/** @var string */
	private $prefix;

	/**
	 * @param string $prefix
	 */
	function __construct($prefix)
	{
		$this->prefix = $prefix;
	}

	/**
	 * @param string $prefix
	 */
	public function setPrefix($prefix)
	{
		if ($this->prefix) {
			parent::setPrefix(rtrim($this->prefix . '/' . trim($prefix, '/'), '/'));
		} else {
			parent::setPrefix($prefix);
		}
	}

}