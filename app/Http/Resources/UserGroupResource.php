<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserGroupResource extends JsonResource
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
			'levelId'          => $this->level_id,
			'level'            => new LevelResource($this->whenLoaded('level')),
			'displayName'      => $this->display_name,
			'description'      => $this->description,
			'permissions'      => PermissionResource::collection($this->whenLoaded('permissions')),
			'permissionsCount' => $this->when(isset($this->permissions_count), $this->permissions_count),
			'users'            => UserResource::collection($this->whenLoaded('users')),
			'usersCount'       => $this->when(isset($this->users_count), $this->users_count),
			'roles'            => RoleResource::collection($this->whenLoaded('roles')),
			'rolesCount'       => $this->when(isset($this->roles_count), $this->roles_count),
			'createdAt'        => $this->created_at,
			'updatedAt'        => $this->updated_at,
			'deletedAt'        => $this->deleted_at,
		];
	}
}
