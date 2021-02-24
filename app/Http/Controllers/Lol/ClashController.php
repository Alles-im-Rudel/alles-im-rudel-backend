<?php

namespace App\Http\Controllers\Lol;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Clash\ClashIndexRequest;
use App\Http\Resources\ClashTeamResource;
use App\Models\ClashTeam;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ClashController extends BaseController
{
	/**
	 * @param  ClashIndexRequest  $request
	 * @return AnonymousResourceCollection
	 */
	public function index(ClashIndexRequest $request): AnonymousResourceCollection
	{
		$clashTeams = ClashTeam::with([
			'clashMembers.clashTeamRole',
			'clashMembers.clashTeam',
			'clashMembers.summoner.leagueEntries',
			'clashMembers.user',
			'leader'
		])->get();

		return ClashTeamResource::collection($clashTeams);
	}
}
