<?php

namespace App\Http\Resources;

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
            'id'               => $this->id,
            'firstName'        => $this->first_name,
            'lastName'         => $this->last_name,
            'fullName'         => $this->first_name.' '.$this->last_name,
            'email'            => $this->email,
            'username'         => $this->username,
            'emailVerifiedAt'  => $this->email_verfied_at,
            'isActive'         => $this->activated_at !== null,
            'permissions'      => PermissionResource::collection($this->whenLoaded('permissions')),
            'permissionsCount' => $this->when(isset($this->permissions_count), $this->permissions_count),
            //'roles'            => RoleResource::collection($this->whenLoaded('roles')),
            'rolesCount'       => $this->when(isset($this->roles_count), $this->roles_count),
            'createdAt'        => $this->created_at,
            'updatedAt'        => $this->updated_at,
            'deletedAt'        => $this->deleted_at,
        ];
    }
}
