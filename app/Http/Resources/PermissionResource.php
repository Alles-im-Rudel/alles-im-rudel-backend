<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param  Request  $request
	 * @return array
	 */
	public function toArray($request): array
	{
		return [
			'id'        => $this->id,
			'name'      => $this->name,
			'guardName' => $this->guard_name,
			'createdAt' => $this->created_at,
			'updatedAt' => $this->updated_at,
		];
	}
}
