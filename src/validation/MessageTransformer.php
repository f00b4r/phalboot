<?php

namespace Phalette\Bootstrap\Validation;

use Phalcon\Mvc\Model\Message as PhMessage;

final class Utils
{

	/**
	 * @param PhMessage[] $messages
	 * @return array
	 */
	public static function toArray(array $messages)
	{
		$output = [];
		foreach ($messages as $messsage) {
			$output[] = [
				'message' => $messsage->getMessage(),
				'type' => $messsage->getType(),
				'field' => $messsage->getField()
			];
		}

		return $output;
	}

}