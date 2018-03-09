<?php

namespace Phalette\Bootstrap\Response\Json;

use App\Library\Http\HttpCodes;

final class FailResponse extends Response
{

	/**
	 * @param array $data
	 * @param string $message
	 * @param int $code
	 */
	function __construct(array $data = [], $message = NULL, $code = HttpCodes::BAD_REQUEST)
	{
		$this->data = $data;
		$this->code = $code;

		if ($message) {
			$this->message = $message;
		}
	}

	/**
	 * @return array
	 */
	public function output()
	{
		$out = [
			'status' => 'fail',
			'data' => $this->data,
			'code' => $this->code,
		];

		if ($this->message) {
			$out['message'] = $this->message;
		}

		return $out;
	}
}
