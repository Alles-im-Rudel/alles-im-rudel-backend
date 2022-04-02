<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
			'id'                     => $this->id,
			'firstName'              => $this->first_name,
			'lastName'               => $this->last_name,
			'fullName'               => $this->first_name.' '.$this->last_name,
			'email'                  => $this->email,
			'birthday'               => $this->birthday ? Carbon::parse($this->birthday)->format('Y-m-d') : '',
			'age'                    => $this->age,
			'emailVerifiedAt'        => $this->email_verified_at,
			'wantsEmailNotification' => $this->wants_email_notification,
			'isActive'               => $this->activated_at !== null,
			'activatedAt'            => $this->activated_at,
			'levelId'                => $this->getMaxLevelId(),
			'memberShip'             => new MemberShipResource($this->whenLoaded('memberShip')),
			'permissions'            => PermissionResource::collection($this->whenLoaded('permissions')),
			'permissionsCount'       => $this->when(isset($this->permissions_count), $this->permissions_count),
			'roles'                  => RoleResource::collection($this->whenLoaded('roles')),
			'rolesCount'             => $this->when(isset($this->roles_count), $this->roles_count),
			'userGroupsCount'        => $this->when(isset($this->user_groups_count), $this->user_groups_count),
			'userGroups'             => UserGroupResource::collection($this->whenLoaded('userGroups')),
			'mainSummoner'           => new SummonerResource($this->whenLoaded('mainSummoner')),
			'image'                  => new ImageResource($this->whenLoaded('image')),
			'thumbnail'              => new ImageResource($this->whenLoaded('thumbnail')),
			'postsCount'             => $this->when(isset($this->posts_count), $this->posts_count),
			'commentsCount'          => $this->when(isset($this->comments_count), $this->comments_count),
			'likedCount'             => $this->when(isset($this->liked_count), $this->liked_count),
			'createdAt'              => $this->created_at,
			'updatedAt'              => $this->updated_at,
			'deletedAt'              => $this->deleted_at,
		];
	}
}
