<?php

namespace App\Http\Resources\Riot;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RiotSummonerSpellResource extends JsonResource
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
			'id'          => $this->id,
			'spellId'     => $this->spell_id,
			'name'        => $this->name,
			'description' => $this->description,
			'tooltip'     => $this->tooltip,
			'cooldown'    => $this->cooldown,
			'image'       => $this->image
		];
	}
}
