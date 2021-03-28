<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use PhpParser\Node\Expr\Array_;

class BaseController extends Controller
{
	/**
	 * @var Builder
	 */
	protected Builder $builder;

	/**
	 * @var array
	 */
	protected array $availableOrderByFields;

	/**
	 * @var array
	 */
	protected array $searchFields;

	/**
	 * @var string
	 */
	protected string $tableName;

	/**
	 * @param  int  $perPage
	 * @param  int  $page
	 * @return LengthAwarePaginator
	 */
	public function paginate(int $perPage, int $page): LengthAwarePaginator
	{
		return $this->builder->paginate($perPage, '*', $page, $page);
	}

	/**
	 * @param  string  $search
	 * @return Builder
	 */
	protected function search(string $search): Builder
	{
		if ($search) {
			$searchFields = $this->searchFields;
			$this->builder->where(function (Builder $builder) use ($search, $searchFields): Builder {
				foreach ($searchFields as $field) {
					$builder->orWhere($field, 'LIKE', $search);
				}
				return $builder;
			});
		}
		return $this->builder;
	}

	/**
	 * @param  string|null  $orderBy
	 * @param  bool|null  $descending
	 * @return Builder
	 */
	public function orderBy(?string $orderBy, ?bool $descending = null): Builder
	{
		if ($orderBy && in_array($orderBy, $this->availableOrderByFields, true)) {
			$this->builder->orderBy($orderBy, $descending === true ? 'DESC' : 'ASC');
		}
		return $this->builder;
	}

	/**
	 * @param  string|null  $jsonArray
	 * @param  string|null  $currentTable
	 * @return Builder
	 */
	public function orderByJson(?string $jsonArray, ?string $currentTable = null): Builder
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

						$currentTable = $this->tableName;
						if ($currentTable) {
							foreach ($parts as $index => $part) {
								if ($index < $countParts - 1) {
									$table = Str::plural($part);
									$primaryTable = $index > 0 ? $table : $currentTable;
									$field = Str::singular($part).'_id';

									$this->builder->join(
										$table,
										$table.'.id',
										'=',
										$primaryTable.'.'.$field
									);
								}
							}

							$this->builder->orderBy($parts[$countParts - 1], $direction)
								->select($currentTable.'.*');
						}

					} else {

						$this->builder->orderBy($field, $direction);

					}

				}
			}
		}

		return $this->builder;
	}

	/**
	 * @param  bool|null  $withOnlyTrashed
	 * @return Builder
	 */
	public function onlyTrashed(?bool $withOnlyTrashed): Builder
	{
		if ($withOnlyTrashed) {
			$this->builder->onlyTrashed();
		}

		return $this->builder;
	}

	/**
	 * @return Builder
	 */
	public function withTrashed(): Builder
	{
		return $this->builder->withTrashed();
	}

	/**
	 * @param  String  $column
	 * @param  array  $array
	 * @return Builder
	 */
	public function whereIn(string $column, array $array): Builder
	{
		return $this->builder->whereIn($column, $array);
	}

	/**
	 * @return Builder[]|Collection
	 */
	public function get()
	{
		return $this->builder->get();
	}

	/**
	 * @return Builder
	 */
	public function getQuery(): Builder
	{
		return $this->builder;
	}
}
