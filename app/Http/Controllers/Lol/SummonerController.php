<?php

namespace App\Http\Controllers\Lol;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Summoner\SummonerActiveGameRequest;
use App\Http\Requests\Summoner\SummonerAttachMainUserRequest;
use App\Http\Requests\Summoner\SummonerDetachMainUserRequest;
use App\Http\Requests\Summoner\SummonerIndexRequest;
use App\Http\Requests\Summoner\SummonerReloadRequest;
use App\Http\Requests\Summoner\SummonerShowRequest;
use App\Http\Resources\ChampionResource;
use App\Http\Resources\Riot\RiotSummonerSpellResource;
use App\Http\Resources\SummonerResource;
use App\Models\Champion;
use App\Models\RiotSummonerIcon;
use App\Models\RiotSummonerSpell;
use App\Models\Summoner;
use App\Traits\Functions\SummonerTrait;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
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
		if ($summoner) {
			$summoner->loadMissing(['mainUser', 'leagueEntries.queueType'])->loadCount('users');

			return response()->json([
				'summoner' => new SummonerResource($summoner)
			], Response::HTTP_OK);
		}
		return response()->json([
			'message' => 'Leider ist ein Fehler aufgeträten bitte versuchen Sie es später erneut.',
		], Response::HTTP_BAD_REQUEST);
	}

	public function activeGame(SummonerActiveGameRequest $request, Summoner $summoner)
	{
		$http = new Client();
		try {
			$response = $http->get(env('RIOT_API_URL').'/lol/spectator/v4/active-games/by-summoner/'.$summoner->summoner_id.'?api_key='.env('RIOT_API_KEY'));
		} catch (GuzzleException $exception) {
			Log::error($exception);
			return response()->json([
				'message' => 'Leider ist ein Fehler aufgeträten bitte versuchen Sie es später erneut.',
			], Response::HTTP_BAD_REQUEST);
		}
		if ($response->getStatusCode() !== 200) {
			return response()->json([
				'message' => 'Nicht in Game.',
			], Response::HTTP_BAD_REQUEST);
		}
		try {
			$gameData = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
		} catch (\Exception $exception) {
			Log::error($exception);
			return response()->json([
				'message' => 'Leider ist ein Fehler aufgeträten bitte versuchen Sie es später erneut.',
			], Response::HTTP_BAD_REQUEST);
		}

		return [
			'gameId'            => $gameData['gameId'],
			'gameLength'        => $gameData['gameLength'],
			'gameMode'          => $gameData['gameMode'],
			'gameQueueConfigId' => $gameData['gameQueueConfigId'],
			'gameStartTime'     => Carbon::parse($gameData['gameStartTime'])->format('Y-m-d H:i'),
			'gameType'          => $gameData['gameType'],
			'mapId'             => $gameData['mapId'],
			'platformId'        => $gameData['platformId'],
			'banData'           => $this->getBanData($gameData['bannedChampions']),
			'observers'         => $gameData['observers'],
			'participants'      => $this->getParticipants($gameData['participants'])
		];
	}

	/**
	 * @param  array  $data
	 * @return \Illuminate\Support\Collection
	 */
	protected function getBanData(array $data): Collection
	{
		$bans = collect($data);

		return $bans->map(function ($item) {
			return [
				'teamId'   => $item['teamId'] === 100 ? 1 : 2,
				'pickTurn' => $item['pickTurn'],
				'champion' => new ChampionResource(Champion::where('key',
					$item['championId'])->first())
			];
		});
	}

	/**
	 * @param  array  $data
	 * @return \Illuminate\Support\Collection
	 */
	protected function getParticipants(array $data): Collection
	{
		$bans = collect($data);

		return $bans->map(function ($item) {
			return [
				'bot'                      => $item['bot'],
				'champion'                 => new ChampionResource(Champion::where('key',
					$item['championId'])->first()),
				'gameCustomizationObjects' => $item['gameCustomizationObjects'],
				'perks'                    => $item['perks'],
				'profileIconId'            => $item['profileIconId'],
				'profileIcon'              => RiotSummonerIcon::where('name', $item['profileIconId'])->first()->image,
				'spell1'                   => new RiotSummonerSpellResource(RiotSummonerSpell::where('key',
					$item['spell1Id'])->first()),
				'spell2'                   => new RiotSummonerSpellResource(RiotSummonerSpell::where('key',
					$item['spell2Id'])->first()),
				'summonerId'               => $item['summonerId'],
				'summonerName'             => $item['summonerName'],
				'teamId'                   => $item['teamId'] === 100 ? 1 : 2,
			];
		});
	}

	/**
	 * @param  \App\Http\Requests\Summoner\SummonerActiveGameRequest  $request
	 * @param  \App\Models\Summoner  $summoner
	 * @return bool
	 */
	public function checkActiveGame(SummonerActiveGameRequest $request, Summoner $summoner): bool
	{
		$http = new Client();
		try {
			$response = $http->get(env('RIOT_API_URL').'/lol/spectator/v4/active-games/by-summoner/'.$summoner->summoner_id.'?api_key='.env('RIOT_API_KEY'));
		} catch (GuzzleException $exception) {
			Log::error($exception);
			return false;
		}
		return $response->getStatusCode() === 200;
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
