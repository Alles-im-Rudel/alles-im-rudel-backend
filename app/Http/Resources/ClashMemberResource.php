<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClashMemberResource extends JsonResource
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
			'id'              => $this->id,
			'name'            => $this->name,
			'isActive'        => $this->is_active,
			'summonerId'      => $this->summoner_id,
			'summoner'        => new SummonerResource($this->whenLoaded('summoner')),
			'userId'          => $this->user_id,
			'user'            => new UserResource($this->whenLoaded('user')),
			'clashTeamRoleId' => $this->clash_team_role_id,
			'clashTeamRole'   => new ClashTeamRoleResource($this->whenLoaded('clashTeamRole')),
			'clashTeamId'     => $this->clash_team_id,
			'clashTeam'       => new ClashTeamResource($this->whenLoaded('clashTeam')),
		];
	}
}
