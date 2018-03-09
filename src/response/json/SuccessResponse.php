<?php

namespace Phalette\Bootstrap\Response\Json;

use App\Library\Http\HttpCodes;

final class SuccessResponse extends Response
{

	/**
	 * @param array $data
	 * @param int $code
	 */
	function __construct(array $data = [], $code = HttpCodes::OK)
	{
		$this->data = $data;
		$this->code = $code;
	}

	/**
	 * @return array
	 */
	public function output()
	{
		$out = [
			'status' => 'success',
			'data' => $this->data,
			'code' => $this->code,
		];

		return $out;
	}
}
