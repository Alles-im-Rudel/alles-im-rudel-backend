<?php

namespace App\Traits\Functions;

use Exception;
use Illuminate\Support\Str;

trait OrderByTraitJson
{

	/**
	 * @var array
	 */
	protected array $availableOrderByFields;

	/**
	 * @param $query
	 * @param  string|null  $jsonArray
	 * @param  string|null  $currentTable
	 * @return mixed
	 */
	protected function orderByJson($query, ?string $jsonArray, ?string $currentTable = null)
	{
		try {
			$decodedArray = json_decode($jsonArray, true, 512, JSON_THROW_ON_ERROR);
		} catch (Exception $exception) {
			$decodedArray = null;
		}

		if ($decodedArray) {
			foreach (array_keys($decodedArray) as $arrayKey) {
				$field = Str::snake($arrayKey);
				if (in_array($field, $this->availableOrderByFields, true)) {

					$parts = explode('.', $field);
					$countParts = count($parts);
					$direction = $decodedArray[$arrayKey] === true ? 'DESC' : 'ASC';

					if ($countParts > 1) {

						$currentTable = $this->getTableName();
						if ($currentTable) {
							foreach ($parts as $index => $part) {
								if ($index < $countParts - 1) {
									$table = Str::plural($part);
									$primaryTable = $index > 0 ? $table : $currentTable;
									$field = Str::singular($part).'_id';

									$query->join(
										$table,
										$table.'.id',
										'=',
										$primaryTable.'.'.$field
									);
								}
							}

							$query->orderBy($parts[$countParts - 1], $direction)
								->select($currentTable.'.*');
						}

					} else {

						$query->orderBy($field, $direction);

					}

				}
			}
		}

		return $query;
	}
}
