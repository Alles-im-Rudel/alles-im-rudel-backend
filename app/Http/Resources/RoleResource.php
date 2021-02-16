<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
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
			'id'               => $this->id,
			'name'             => $this->name,
			'updatedAt'        => $this->updated_at,
			'permissions'      => PermissionResource::collection($this->whenLoaded('permissions')),
			'permissionsCount' => $this->when($this->permissions_count !== null, $this->permissions_count),
			'users'            => UserResource::collection($this->whenLoaded('users')),
			'usersCount'       => $this->when($this->users_count !== null, $this->users_count),
			'userGroups'       => UserGroupResource::collection($this->whenLoaded('userGroups')),
			'userGroupsCount'  => $this->when($this->user_groups_count !== null, $this->user_groups_count),
		];
	}
}
