<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchUserMemberShipResource extends JsonResource
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
			'id'             => $this->id,
			'isActive'       => $this->is_active,
			'isExported'     => $this->is_exported,
			'wantsToLeave'   => $this->wants_to_leave,
			'activatedAt'    => $this->activated_at,
			'wantsToLeaveAt' => $this->wants_to_leave_at,
			'exportedAt'     => $this->exported_at,
			'state'          => $this->state,
			'sepaDate'       => $this->sepa_date,
			'userId'         => $this->user_id,
			'branchId'       => $this->branch_id,
			'branch'         => new BranchResource($this->whenLoaded('branch')),
			'user'           => new UserResource($this->whenLoaded('user')),
			'createdAt'      => $this->created_at,
			'updatedAt'      => $this->updated_at,
			'deletedAt'      => $this->deleted_at,
		];
	}
}
