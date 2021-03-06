<?php

namespace App\Http\Controllers\Lol;

use App\Http\Controllers\Controller;
use App\Http\Requests\Summoner\SummonerPickerIndexRequest;
use App\Http\Resources\SummonerResource;
use App\Traits\Functions\SummonerTrait;
use Illuminate\Http\Response;

class SummonerPickerController extends Controller
{
	use SummonerTrait;

	public function index(SummonerPickerIndexRequest $request)
	{

		$summoner = $this->getSummonerByName($request->search);
		if (!$summoner) {
			return response()->json([
				'message' => "Es wurde kein Summoner mit dem Namen \"{$request->search}\" gefunden.",
			], Response::HTTP_BAD_REQUEST);
		}

		if ($request->freeMain && $summoner->main_user_id !== null) {
			return response()->json([
				'message' => "Der Summoner \"{$request->search}\" gehört schon zu jemandem.",
			], Response::HTTP_BAD_REQUEST);
		}

		$this->reloadSummoner($summoner);

		$summoner->refresh()->loadMissing('leagueEntries.queueType');

		return new SummonerResource($summoner);
	}
}
