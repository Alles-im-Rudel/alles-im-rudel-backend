<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClashTeamResource extends JsonResource
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
			'id'           => $this->id,
			'name'         => $this->name,
			'leaderId'     => $this->leader_id,
			'leader'       => new UserResource($this->whenLoaded('leader')),
			'clashMembers' => ClashMemberResource::collection($this->whenLoaded('clashMembers')),
		];
	}
}
