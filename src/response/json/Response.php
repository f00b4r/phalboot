<?php

namespace Phalette\Bootstrap\Response\Json;

use App\Library\Http\HttpCodes;
use Phalcon\Http\Response as PhResponse;

abstract class Response
{

	/** @var string */
	protected $data;

	/** @var int */
	protected $code;

	/** @var string */
	protected $message;

	/**
	 * @return string
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * @param string $data
	 */
	public function setData($data)
	{
		$this->data = $data;
	}

	/**
	 * @return int
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * @param int $code
	 */
	public function setCode($code)
	{
		$this->code = $code;
	}

	/**
	 * @return string
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * @param string $message
	 */
	public function setMessage($message)
	{
		$this->message = $message;
	}

	/**
	 * @param mixed $data
	 * @param int $options
	 * @return mixed|string
	 */
	public function toJson($data, $options = 0)
	{
		$flags = PHP_VERSION_ID >= 50400 ? (JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | ($options & JSON_PRETTY_PRINT ? JSON_PRETTY_PRINT : 0)) : 0;
		$json = json_encode($data, $flags);
		$json = str_replace(array("\xe2\x80\xa8", "\xe2\x80\xa9"), array('\u2028', '\u2029'), $json);
		return $json;
	}

	/**
	 * @param PhResponse $response
	 */
	public function decorate(PhResponse $response)
	{
		$response->setContentType('application/json', 'UTF-8');
		$response->setJsonContent($this->output());
		$response->setStatusCode(HttpCodes::OK);
	}

	/**
	 * @return mixed
	 */
	abstract function output();
}
