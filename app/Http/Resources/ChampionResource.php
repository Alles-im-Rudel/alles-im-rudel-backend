<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChampionResource extends JsonResource
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
			'id'         => $this->id,
			'championId' => $this->champion_id,
			'key'        => $this->key,
			'name'       => $this->name,
			'title'      => $this->title,
			'blurb'      => $this->blurb,
			'info'       => $this->info,
			'image'      => $this->image,
			'tags'       => $this->tags,
			'partype'    => $this->partype,
			'stats'      => $this->stats,
			'icon'       => $this->icon,
			'splashArt'  => $this->splash_art
		];
	}
}
