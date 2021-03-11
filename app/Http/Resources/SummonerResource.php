<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SummonerResource extends JsonResource
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
			'accountId'     => $this->account_id,
			'profileIconId' => $this->profile_icon_id,
			'revisionDate'  => $this->revision_date,
			'name'          => $this->name,
			'summonerId'    => $this->summoner_id,
			'puuid'         => $this->puuid,
			'summonerLevel' => $this->summoner_level,
			'mainUserId'    => $this->main_user_id,
			'mainUser'      => new UserResource($this->whenLoaded('mainUser')),
			'leagueEntries' => LeagueEntryResource::collection($this->whenLoaded('leagueEntries')),
			'usersCount'    => $this->when(isset($this->users_count), $this->users_count),
			'users'         => UserResource::collection($this->whenLoaded('users')),
			'createdAt'     => $this->created_at,
			'updatedAt'     => $this->updated_at
		];
	}
}
