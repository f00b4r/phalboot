<?php

namespace Phalette\Bootstrap\Response\Json;

final class SimpleResponse extends Response
{

	/**
	 * @param array $data
	 * @param int $code
	 */
	function __construct(array $data = [], $code = NULL)
	{
		$this->data = $data;
		$this->code = $code;
	}

	/**
	 * @return array
	 */
	public function output()
	{
		$out = ['data' => $this->data];

		if ($this->code) {
			$out['code'] = $this->code;
		}

		return $out;
	}
}
