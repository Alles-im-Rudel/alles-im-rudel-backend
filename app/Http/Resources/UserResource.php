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
			'id'                         => $this->id,
			'salutation'                 => $this->salutation,
			'firstName'                  => $this->first_name,
			'lastName'                   => $this->last_name,
			'fullName'                   => $this->full_name,
			'email'                      => $this->email,
			'phone'                      => $this->phone,
			'street'                     => $this->street,
			'postcode'                   => $this->postcode,
			'city'                       => $this->city,
			'birthday'                   => $this->birthday ? Carbon::parse($this->birthday)->format('Y-m-d') : '',
			'age'                        => $this->age,
			'emailVerifiedAt'            => $this->email_verified_at,
			'wantsEmailNotification'     => $this->wants_email_notification,
			'isActive'                   => $this->activated_at !== null,
			'activatedAt'                => $this->activated_at,
			'levelId'                    => $this->getMaxLevelId(),
			'country'                    => new CountryResource($this->whenLoaded('country')),
			'bankAccount'                => new BankAccountResource($this->whenLoaded('bankAccount')),
			'branchUserMemberShips'      => BranchUserMemberShipResource::collection($this->whenLoaded('branchUserMemberShips')),
			'permissions'                => PermissionResource::collection($this->whenLoaded('permissions')),
			'permissionsCount'           => $this->when(isset($this->permissions_count), $this->permissions_count),
			'roles'                      => RoleResource::collection($this->whenLoaded('roles')),
			'rolesCount'                 => $this->when(isset($this->roles_count), $this->roles_count),
			'userGroupsCount'            => $this->when(isset($this->user_groups_count), $this->user_groups_count),
			'branchUserMemberShipsCount' => $this->when(isset($this->branch_user_member_ships_count),
				$this->branch_user_member_ships_count),
			'userGroups'                 => UserGroupResource::collection($this->whenLoaded('userGroups')),
			'mainSummoner'               => new SummonerResource($this->whenLoaded('mainSummoner')),
			'image'                      => new ImageResource($this->whenLoaded('image')),
			'thumbnail'                  => new ImageResource($this->whenLoaded('thumbnail')),
			'postsCount'                 => $this->when(isset($this->posts_count), $this->posts_count),
			'commentsCount'              => $this->when(isset($this->comments_count), $this->comments_count),
			'likedCount'                 => $this->when(isset($this->liked_count), $this->liked_count),
			'createdAt'                  => $this->created_at,
			'updatedAt'                  => $this->updated_at,
			'deletedAt'                  => $this->deleted_at,
		];
	}
}
