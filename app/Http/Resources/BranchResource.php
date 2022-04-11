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
			'id'                    => $this->id,
			'name'                  => $this->name,
			'description'           => $this->description,
			'price'                 => $this->price,
			'activatedAt'           => $this->activated_at,
			'isActive'              => $this->is_active,
			'isSelectable'          => $this->is_selectable,
			'membersCount'          => $this->when($this->members_count !== null, $this->members_count),
			'branchUserMemberShips' => BranchUserMemberShipResource::collection($this->whenLoaded('branchUserMemberShips')),
		];
	}
}
