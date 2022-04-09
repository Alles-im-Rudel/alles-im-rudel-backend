<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MemberShipResource extends JsonResource
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
			'id'           => $this->id,
			'userId'       => $this->user_id,
			'countryId'    => $this->country_id,
			'phone'        => $this->phone,
			'street'       => $this->street,
			'postcode'     => $this->postcode,
			'city'         => $this->city,
			'iban'         => $this->iban,
			'bic'          => $this->bic,
			'activatedAt'  => $this->activated_at,
			'isActive'     => $this->is_active,
			'user'         => new UserResource($this->whenLoaded('user')),
			'country'      => new CountryResource($this->whenLoaded('country')),
			'branches'     => BranchResource::collection($this->whenLoaded('branches')),
			'contactTypes' => ContactTypeResource::collection($this->whenLoaded('contactTypes')),
			'signature'    => new ImageResource($this->whenLoaded('signature')),
			'createdAt'    => $this->created_at,
			'updatedAt'    => $this->updated_at
		];
	}
}
