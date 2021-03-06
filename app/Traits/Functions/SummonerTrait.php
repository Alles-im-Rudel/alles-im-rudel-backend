<?php

namespace App\Traits\Functions;

use App\Models\Summoner;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

trait SummonerTrait
{

	/**
	 * @param $name
	 * @return mixed
	 */
	public function getSummonerByName($name)
	{
		$summoner = Summoner::where('name', $name)->first();
		if ($summoner) {
			return $summoner;
		}

		$http = new Client();
		try {
			$response = $http->get(env('RIOT_API_URL').'/lol/summoner/v4/summoners/by-name/'.$name.'?api_key='.env('RIOT_API_KEY'));
		} catch (GuzzleException $exception) {
			Log::error($exception);
			return null;
		}
		try {
			$summonerData = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
		} catch (\JsonException $exception) {
			Log::error($exception);
			return null;
		}
		return Summoner::create([
			'account_id'      => $summonerData['accountId'],
			'profile_icon_id' => (int) $summonerData['profileIconId'],
			'revision_date'   => Carbon::parse($summonerData['revisionDate']),
			'name'            => $summonerData['name'],
			'summoner_id'     => $summonerData['id'],
			'puuid'           => $summonerData['puuid'],
			'summoner_level'  => $summonerData['summonerLevel'],
		]);
	}

	/**
	 * @param  Summoner  $summoner

	 * @return bool
	 */
	public function reloadSummoner(Summoner $summoner): bool
	{

		$http = new Client();
		try {
			$response = $http->get(env('RIOT_API_URL').'/lol/summoner/v4/summoners/by-account/'.$summoner->account_id.'?api_key='.env('RIOT_API_KEY'));
		} catch (GuzzleException $exception) {
			Log::error($exception);
			return false;
		}
		try {
			$summonerData = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
		} catch (\JsonException $exception) {
			Log::error($exception);
			return false;
		}
		$summoner->update([
			'account_id'      => $summonerData['accountId'],
			'profile_icon_id' => (int) $summonerData['profileIconId'],
			'revision_date'   => Carbon::parse($summonerData['revisionDate']),
			'name'            => $summonerData['name'],
			'summoner_id'     => $summonerData['id'],
			'puuid'           => $summonerData['puuid'],
			'summoner_level'  => $summonerData['summonerLevel'],
		]);

		$http = new Client();
		try {
			$response = $http->get(env('RIOT_API_URL').'/lol/league/v4/entries/by-summoner/'.$summoner->summoner_id.'?api_key='.env('RIOT_API_KEY'));
		} catch (GuzzleException $exception) {
			Log::error($exception);
			return false;
		}
		try {
			$leagueEntries = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
		} catch (\JsonException $exception) {
			Log::error($exception);
			return false;
		}
		foreach ($leagueEntries as $leagueEntry) {
			$summoner->leagueEntries()->updateOrCreate(
				[
					'summoner_id' => $leagueEntry['summonerId'],
					'queue_type'  => $leagueEntry['queueType']
				], [
				'league_id'     => $leagueEntry['leagueId'],
				'tier'          => $leagueEntry['tier'],
				'rank'          => $leagueEntry['rank'],
				'league_points' => (int) $leagueEntry['leaguePoints'],
				'wins'          => (int) $leagueEntry['wins'],
				'losses'        => (int) $leagueEntry['losses'],
				'hot_streak'    => (bool) $leagueEntry['hotStreak'],
				'veteran'       => (bool) $leagueEntry['veteran'],
				'fresh_blood'   => (bool) $leagueEntry['freshBlood'],
				'inactive'      => (bool) $leagueEntry['inactive'],
			]);
		}
		return true;
	}
}