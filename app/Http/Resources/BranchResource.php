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
			'id'                   => $this->id,
			'name'                 => $this->name,
			'description'          => $this->description,
			'price'                => $this->price,
			'activatedAt'          => $this->activated_at,
			'isActive'             => $this->is_active,
			'isSelectable'         => $this->is_selectable,
			'membersCounts'        => $this->members_counts,
			'pivotId'              => $this->pivot->id ?? null,
			'pivotDeletedAt'       => $this->pivot->deleted_at ?? null,
			'pivotUpdatedAt'       => $this->pivot->updated_at ?? null,
			'pivotCreatedAt'       => $this->pivot->created_at ?? null,
			'pivotexportedAt'      => $this->pivot->exported_at ?? null,
			'pivotWantedToLeaveAt' => $this->pivot->wanted_to_leave_at ?? null,
			'pivotActivatedAt'     => $this->pivot->activated_at ?? null,
			'members'              => MemberShipResource::collection($this->whenLoaded('members')),
		];
	}
}
