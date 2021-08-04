<?php

namespace App\Http\Controllers\Lol;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Summoner\SummonerInfoIndexRequest;
use App\Http\Resources\ChampionResource;
use App\Http\Resources\Riot\RiotSummonerSpellResource;
use App\Models\Champion;
use App\Models\RiotItems;
use App\Models\RiotSummonerIcon;
use App\Models\RiotSummonerSpell;
use App\Models\Summoner;
use App\Traits\Functions\SummonerTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class SummonerInfoController extends BaseController
{
	use SummonerTrait;

	/**
	 * @param  \App\Models\Summoner  $summoner
	 * @return array
	 */
	public function champions(Summoner $summoner): array
	{
		$summoner = $this->getSummonerByName($summoner->name);

		try {
			$masteryScore = Http::get(env('RIOT_API_URL').'/lol/champion-mastery/v4/scores/by-summoner/'.$summoner->summoner_id.'?api_key='.env('RIOT_API_KEY'))->body();
		} catch (\Exception $exception) {
			$masteryScore = 0;
		}
		try {
			$championsJson = Http::get(env('RIOT_API_URL').'/lol/champion-mastery/v4/champion-masteries/by-summoner/'.$summoner->summoner_id.'?api_key='.env('RIOT_API_KEY'))->json();

			$champions = $this->getChampionsData(collect($championsJson));
		} catch (\Exception $exception) {
			$champions = [];
		}


		return [
			'champions'    => $champions,
			'masteryScore' => $masteryScore,
		];
	}

	/**
	 * @param  \App\Models\Summoner  $summoner
	 * @param  \App\Http\Requests\Summoner\SummonerInfoIndexRequest  $request
	 * @return array|\Illuminate\Support\Collection
	 */
	public function matches(Summoner $summoner, SummonerInfoIndexRequest $request)
	{
		$summoner = $this->getSummonerByName($summoner->name);

		try {
			$matchesJson = Http::get(env('RIOT_API_MATCH_URL').'/lol/match/v5/matches/by-puuid/'.$summoner->puuid.'/ids?start='.$request->beginIndex.'&count='.$request->endIndex.'&api_key='.env('RIOT_API_KEY'))->json();
		} catch (\Exception $exception) {
			return [];
		}
		if (count($matchesJson) > 0) {
			return $this->getMatches(collect($matchesJson));
		}
		return [];
	}

	/**
	 * @param  \Illuminate\Support\Collection  $matches
	 * @return \Illuminate\Support\Collection
	 */
	protected function getMatches(Collection $matches): Collection
	{
		return $matches->map(function ($item) {
			return [
				'gameId'   => $item,
				'gameData' => $this->getMatch($item)
			];
		});
	}

	/**
	 * @param  string  $gameId
	 * @return array
	 */
	protected function getMatch(string $gameId): array
	{
		$match = Http::get(env('RIOT_API_MATCH_URL').'/lol/match/v5/matches/'.$gameId.'?api_key='.env('RIOT_API_KEY'))->json();

		return [
			'gameId'       => $match['info']['gameId'],
			'gameCreation' => $match['info']['gameCreation'],
			'gameDuration' => $match['info']['gameDuration'],
			'gameVersion'  => $match['info']['gameVersion'],
			'gameMode'     => $match['info']['gameMode'],
			'gameType'     => $match['info']['gameType'],
			'teams'        => $this->getTeams($match['info']['teams'], $match['info']['participants'])
		];
	}

	/**
	 * @param  array  $teams
	 * @param  array  $participants
	 * @return array
	 */
	protected function getTeams(array $teams, array $participants): array
	{
		$teamMaped = [];
		foreach ($teams as $index => $team) {
			$teamMaped[$index] = [
				'teamId'     => $team['teamId'],
				'win'        => $team['win'],
				'bans'       => $this->getBans(collect($team['bans'])),
				'objectives' => $team['objectives'],
				'summoners'  => $this->getSummoners($team['teamId'], collect($participants))
			];
		}
		return $teamMaped;
	}

	/**
	 * @param  string  $teamId
	 * @param  \Illuminate\Support\Collection  $participants
	 * @return array
	 */
	protected function getSummoners(
		string $teamId,
		Collection $participants
	): array {
		$teamMembers = $participants->where('teamId', $teamId)->all();
		$summoners = [];
		$index = 0;
		foreach ($teamMembers as $item) {
			$summoners[$index] = [
				'participantId' => $item['participantId'],
				'teamId'        => $item['teamId'],
				'championId'    => $item['championId'],
				'champion'      => new ChampionResource(Champion::where('key',
					$item['championId'])->first() ?: Champion::getDefault()),
				'spell1'        => new RiotSummonerSpellResource(RiotSummonerSpell::where('key',
					$item['summoner1Id'])->first()),
				'spell2'        => new RiotSummonerSpellResource(RiotSummonerSpell::where('key',
					$item['summoner2Id'])->first()),
				'stats'         => $this->getStats($item),
				'lane'          => $item['lane'],
				'summoner'      => $this->getSummoner($item)
			];
			$index++;
		}
		return $summoners;
	}

	/**
	 * @param $item
	 * @return array
	 */
	protected function getSummoner($item): array
	{
		return [
			'name'        => $item['summonerName'],
			'profileIcon' => RiotSummonerIcon::where('name', $item['profileIcon'])->first()->image,
			'summonerId'  => $item['summonerId'],
		];
	}

	/**
	 * @param $item
	 * @return array
	 */
	protected function getStats($item): array
	{
		return [
			'assists'                        => $item['assists'],
			'champLevel'                     => $item['champLevel'],
			'damageDealtToObjectives'        => $item['damageDealtToObjectives'],
			'damageDealtToTurrets'           => $item['damageDealtToTurrets'],
			'damageSelfMitigated'            => $item['damageSelfMitigated'],
			'deaths'                         => $item['deaths'],
			'doubleKills'                    => $item['doubleKills'],
			'firstBloodAssist'               => $item['firstBloodAssist'],
			'firstTowerAssist'               => $item['firstTowerAssist'],
			'firstTowerKill'                 => $item['firstTowerKill'],
			'goldEarned'                     => $item['goldEarned'],
			'goldSpent'                      => $item['goldSpent'],
			'inhibitorKills'                 => $item['inhibitorKills'],
			'item0'                          => RiotItems::where('item_id',
				$item['item0'])->exists() ? RiotItems::where('item_id', $item['item0'])->first()->image : '',
			'item1'                          => RiotItems::where('item_id',
				$item['item1'])->exists() ? RiotItems::where('item_id', $item['item1'])->first()->image : '',
			'item2'                          => RiotItems::where('item_id',
				$item['item2'])->exists() ? RiotItems::where('item_id', $item['item2'])->first()->image : '',
			'item3'                          => RiotItems::where('item_id',
				$item['item3'])->exists() ? RiotItems::where('item_id', $item['item3'])->first()->image : '',
			'item4'                          => RiotItems::where('item_id',
				$item['item4'])->exists() ? RiotItems::where('item_id', $item['item4'])->first()->image : '',
			'item5'                          => RiotItems::where('item_id',
				$item['item5'])->exists() ? RiotItems::where('item_id', $item['item5'])->first()->image : '',
			'item6'                          => RiotItems::where('item_id',
				$item['item6'])->exists() ? RiotItems::where('item_id', $item['item6'])->first()->image : '',
			'killingSprees'                  => $item['killingSprees'],
			'kills'                          => $item['kills'],
			'largestCriticalStrike'          => $item['largestCriticalStrike'],
			'largestKillingSpree'            => $item['largestKillingSpree'],
			'largestMultiKill'               => $item['largestMultiKill'],
			'longestTimeSpentLiving'         => $item['longestTimeSpentLiving'],
			'magicDamageDealt'               => $item['magicDamageDealt'],
			'magicDamageDealtToChampions'    => $item['magicDamageDealtToChampions'],
			'neutralMinionsKilled'           => $item['neutralMinionsKilled'],
			'participantId'                  => $item['participantId'],
			'pentaKills'                     => $item['pentaKills'],
			'perks'                          => $item['perks'],
			'physicalDamageDealt'            => $item['physicalDamageDealt'],
			'physicalDamageDealtToChampions' => $item['physicalDamageDealtToChampions'],
			'physicalDamageTaken'            => $item['physicalDamageTaken'],
			'sightWardsBoughtInGame'         => $item['sightWardsBoughtInGame'],
			'timeCCingOthers'                => $item['timeCCingOthers'],
			'totalDamageDealt'               => $item['totalDamageDealt'],
			'totalDamageDealtToChampions'    => $item['totalDamageDealtToChampions'],
			'totalDamageTaken'               => $item['totalDamageTaken'],
			'totalHeal'                      => $item['totalHeal'],
			'totalMinionsKilled'             => $item['totalMinionsKilled'],
			'totalUnitsHealed'               => $item['totalUnitsHealed'],
			'tripleKills'                    => $item['tripleKills'],
			'trueDamageDealt'                => $item['trueDamageDealt'],
			'trueDamageDealtToChampions'     => $item['trueDamageDealtToChampions'],
			'trueDamageTaken'                => $item['trueDamageTaken'],
			'turretKills'                    => $item['turretKills'],
			'turretTakedowns'                => $item['turretTakedowns'],
			'turretsLost'                    => $item['turretsLost'],
			'unrealKills'                    => $item['unrealKills'],
			'visionScore'                    => $item['visionScore'],
			'visionWardsBoughtInGame'        => $item['visionWardsBoughtInGame'],
			'wardsKilled'                    => $item['wardsKilled'],
			'wardsPlaced'                    => $item['wardsPlaced'],
			'win'                            => $item['win'],
		];
	}

	/**
	 * @param  \Illuminate\Support\Collection  $bans
	 * @return \Illuminate\Support\Collection
	 */
	protected function getBans(Collection $bans): Collection
	{
		return $bans->map(function ($item) {
			return [
				'pickTurn'   => $item['pickTurn'],
				'championId' => $item['championId'],
				'champion'   => new ChampionResource(Champion::where('key',
					$item['championId'])->first() ?: Champion::getDefault()),
			];
		});
	}

	/**
	 * @param  \Illuminate\Support\Collection  $champions
	 * @return \Illuminate\Support\Collection
	 */
	protected function getChampionsData(Collection $champions): Collection
	{
		return $champions->map(function ($item) {
			return [
				'championLevel'                => $item['championLevel'],
				'championId'                   => $item['championId'],
				'champion'                     => new ChampionResource(Champion::where('key',
					$item['championId'])->first() ?: Champion::getDefault()),
				'championPoints'               => $item['championPoints'],
				'chestGranted'                 => $item['chestGranted'],
				'championPointsUntilNextLevel' => $item['championPointsUntilNextLevel']
			];
		});
	}
}
