<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BankAccountResource extends JsonResource
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
			'id'            => $this->id,
			'iban'          => $this->iban,
			'bic'           => $this->bic,
			'firstName'     => $this->first_name,
			'lastName'      => $this->last_name,
			'fullName'      => $this->full_name,
			'street'        => $this->street,
			'postcode'      => $this->postcode,
			'city'          => $this->city,
			'signatureCity' => $this->signature_city,
			'country'       => new CountryResource($this->whenLoaded('country')),
			'signature'     => new ImageResource($this->whenLoaded('signature')),
			'createdAt'     => $this->created_at,
			'updatedAt'     => $this->updated_at,
			'deletedAt'     => $this->deleted_at,
		];
	}
}
