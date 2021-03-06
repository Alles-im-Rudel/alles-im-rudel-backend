<?php

namespace App\Http\Controllers\Lol;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Summoner\SummonerAttachMainUserRequest;
use App\Http\Requests\Summoner\SummonerDetachMainUserRequest;
use App\Http\Requests\Summoner\SummonerEntriesRequest;
use App\Http\Requests\Summoner\SummonerIndexRequest;
use App\Http\Requests\Summoner\SummonerReloadRequest;
use App\Http\Requests\Summoner\SummonerShowRequest;
use App\Http\Resources\SummonerResource;
use App\Models\Summoner;
use App\Traits\Functions\SummonerTrait;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class SummonerController extends BaseController
{
	use SummonerTrait;

	/**
	 * UserController constructor.
	 */
	public function __construct()
	{
		$this->builder = Summoner::query();
		$this->tableName = 'users';
		$this->availableOrderByFields = [
			'name',
			'main_user_id',
			'updated_at',
		];
		$this->searchFields = [
			'name'
		];
	}


	/**
	 * @param  SummonerIndexRequest  $request
	 * @return AnonymousResourceCollection
	 */
	public function index(SummonerIndexRequest $request): AnonymousResourceCollection
	{
		$this->orderByJson($request->sortBy);
		$this->onlyTrashed($request->onlyTrashed);
		$this->search("%{$request->search}%")
			->with('mainUser')
			->withCount('users');

		return SummonerResource::collection($this->paginate($request->perPage, $request->page));
	}


	/**
	 * @param  SummonerShowRequest  $request
	 * @return JsonResponse
	 */
	public function show(SummonerShowRequest $request): JsonResponse
	{
		$summoner = $this->getSummonerByName($request->summonerName);
		if($summoner) {
			$summoner->loadMissing(['mainUser', 'leagueEntries.queueType'])->loadCount('users');

			return response()->json([
				'summoner' => new SummonerResource($summoner)
			], Response::HTTP_OK);
		}
		return response()->json([
			'message' => 'Leider ist ein Fehler aufgeträten bitte versuchen Sie es später erneut.',
		], Response::HTTP_BAD_REQUEST);
	}

	/**
	 * @param  SummonerReloadRequest  $request
	 * @param  Summoner  $summoner
	 * @return JsonResponse
	 */
	public function reload(SummonerReloadRequest $request, Summoner $summoner): JsonResponse
	{
		if ($this->reloadSummoner($summoner)) {
			return response()->json([
				'message' => 'Der Summoner wurde erfolgreich aktualisiert.',
			], Response::HTTP_OK);
		}
		return response()->json([
			'message' => 'Leider ist ein Fehler aufgeträten bitte versuchen Sie es später erneut.',
		], Response::HTTP_BAD_REQUEST);
	}

	/**
	 * @param  SummonerDetachMainUserRequest  $request
	 * @param  Summoner  $summoner
	 * @return JsonResponse
	 */
	public function detachMainUser(SummonerDetachMainUserRequest $request, Summoner $summoner): JsonResponse
	{
		$summoner->update([
			'main_user_id' => null
		]);
		return response()->json([
			'message' => 'Der Summoner wurde erfolgreich von dem Benutzer getrennt.',
		], Response::HTTP_OK);
	}

	/**
	 * @param  SummonerAttachMainUserRequest  $request
	 * @param  Summoner  $summoner
	 * @return JsonResponse
	 */
	public function attachMainUser(SummonerAttachMainUserRequest $request, Summoner $summoner): JsonResponse
	{
		$summoner->update([
			'main_user_id' => $request->userId
		]);
		return response()->json([
			'message' => 'Der Summoner wurde erfolgreich mit dem Benutzer verknüpft.',
		], Response::HTTP_OK);
	}
}
