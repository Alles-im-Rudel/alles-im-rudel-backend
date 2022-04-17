<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param $request
	 * @return array
	 */
	public function toArray($request): array
	{

		return [
			'id'   => $this->id,
			'name' => $this->name,
			'isoCode'  => $this->iso_code,
		];
	}
}
