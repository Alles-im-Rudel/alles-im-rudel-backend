<?php

namespace App\Http\Controllers\Lol;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Summoner\SummonerIndexRequest;
use App\Http\Requests\Summoner\SummonerReloadRequest;
use App\Http\Requests\Summoner\SummonerShowRequest;
use App\Http\Resources\SummonerResource;
use App\Models\Summoner;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class SummonerController extends BaseController
{
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
	 * @throws \JsonException
	 */
	public function show(SummonerShowRequest $request): JsonResponse
	{
		$summoner = Summoner::where('name', $request->summonerName)
			->with('mainUser')
			->withCount('users')
			->first();
		if ($summoner) {
			return response()->json([
				'message'  => 'Der Lol Benutzer wurde erfolgreich gelöscht.',
				'summoner' => new SummonerResource($summoner)
			], Response::HTTP_OK);

		}

		$http = new Client();
		try {
			$response = $http->get(env('RIOT_API_URL').'/lol/summoner/v4/summoners/by-name/'.$request->summonerName.'?api_key='.env('RIOT_API_KEY'));
		} catch (GuzzleException $exception) {
			return response()->json([
				'message' => 'Voll der Fehler.',
			]);
		}
		$summonerData = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
		$summoner = Summoner::create([
			'account_id'      => $summonerData['accountId'],
			'profile_icon_id' => (int) $summonerData['profileIconId'],
			'revision_date'   => Carbon::parse($summonerData['revisionDate']),
			'name'            => $summonerData['name'],
			'summoner_id'     => $summonerData['id'],
			'puuid'           => $summonerData['puuid'],
			'summoner_level'  => $summonerData['summonerLevel'],
		]);
		$summoner->loadMissing('mainUser')->loadCount('user');

		return response()->json([
			'message'  => 'Der Lol Benutzer wurde erfolgreich gelöscht.',
			'summoner' => new SummonerResource($summoner)
		], Response::HTTP_OK);
	}

	/**
	 * @param  SummonerReloadRequest  $request
	 * @param  Summoner  $summoner
	 * @return JsonResponse
	 * @throws \JsonException
	 */
	public function reload(SummonerReloadRequest $request, Summoner $summoner): JsonResponse
	{
		$http = new Client();
		try {
			$response = $http->get(env('RIOT_API_URL').'/lol/summoner/v4/summoners/by-account/'.$summoner->account_id.'?api_key='.env('RIOT_API_KEY'));
		} catch (GuzzleException $exception) {
			return response()->json([
				'message' => 'Voll der Fehler.',
			]);
		}
		$summonerData = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
		$summoner->update([
			'account_id'      => $summonerData['accountId'],
			'profile_icon_id' => (int) $summonerData['profileIconId'],
			'revision_date'   => Carbon::parse($summonerData['revisionDate']),
			'name'            => $summonerData['name'],
			'summoner_id'     => $summonerData['id'],
			'puuid'           => $summonerData['puuid'],
			'summoner_level'  => $summonerData['summonerLevel'],
		]);

		return response()->json([
			'message' => 'Der Summoner wurde erfolgreich aktualiesert',
		], Response::HTTP_OK);
	}
}