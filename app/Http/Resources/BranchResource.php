<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
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
			'id'          => $this->id,
			'name'        => $this->name,
			'price'       => $this->price,
			'activatedAt' => $this->activated_at,
			'isActive'    => $this->is_active,
			'users'       => UserResource::collection($this->whenLoaded('users')),
		];
	}
}
