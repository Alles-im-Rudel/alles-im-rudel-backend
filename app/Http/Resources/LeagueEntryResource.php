<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeagueEntryResource extends JsonResource
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
			'id'           => $this->id,
			'leagueId'     => $this->league_id,
			'summonerId'   => $this->summoner_id,
			'queueTypeId'  => $this->queue_type,
			'tier'         => $this->tier,
			'rank'         => $this->rank,
			'leaguePoints' => $this->league_points,
			'wins'         => $this->wins,
			'losses'       => $this->losses,
			'gamesPlayed'  => $this->wins + $this->losses,
			'hotStreak'    => $this->hot_streak,
			'veteran'      => $this->veteran,
			'freshBlood'   => $this->fresh_blood,
			'inactive'     => $this->inactive,
			'summoner'     => new SummonerResource($this->whenLoaded('summoner')),
			'queueType'    => new QueueTypeResource($this->whenLoaded('queueType')),
		];
	}
}
