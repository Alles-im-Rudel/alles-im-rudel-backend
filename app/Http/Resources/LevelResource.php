<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LevelResource extends JsonResource
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
			'displayName' => $this->display_name,
			'createdAt'   => $this->created_at,
			'updatedAt'   => $this->updated_at,
			'deletedAt'   => $this->deleted_at,

			'users'           => UserResource::collection($this->whenLoaded('users')),
			'usersCount'      => $this->when($this->users_count !== null, $this->users_count),
			'userGroups'      => UserGroupResource::collection($this->whenLoaded('userGroups')),
			'userGroupsCount' => $this->when($this->user_groups_count !== null, $this->user_groups_count),
		];
	}
}
