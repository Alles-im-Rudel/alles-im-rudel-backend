<?php

namespace App\Traits\Functions;

use Exception;

trait DecodeJson
{
	/**
	 * @param string|null $json
	 * @return array|null
	 */
	protected function decodeJson(?string $json): ?array
	{
		if (!$json) {
			return null;
		}

		if ($json === '[]') {
			return [];
		}

		try {
			return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
		} catch (Exception $exception) {
			return null;
		}
	}
}