<?php

namespace Phalette\Bootstrap\Response\Json;

use App\Library\Http\HttpCodes;

final class ErrorResponse extends Response
{

	/**
	 * @param string $message
	 * @param int $code
	 * @param array $data
	 */
	function __construct($message, $code = HttpCodes::INTERNAL_SERVER_ERROR, array $data = [])
	{
		$this->message = $message;
		$this->code = $code;
		$this->data = $data;
	}

	/**
	 * @return array
	 */
	public function output()
	{
		$out = [
			'status' => 'error',
			'message' => $this->message,
			'code' => $this->code,
		];

		if ($this->data) {
			$out['data'] = $this->data;
		}

		return $out;
	}

}
