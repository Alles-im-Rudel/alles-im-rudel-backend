<?php

namespace App\Http\Controllers\Lol;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Summoner\SummonerActiveGameRequest;
use App\Http\Requests\Summoner\SummonerAttachMainUserRequest;
use App\Http\Requests\Summoner\SummonerDetachMainUserRequest;
use App\Http\Requests\Summoner\SummonerIndexRequest;
use App\Http\Requests\Summoner\SummonerInfoIndexRequest;
use App\Http\Requests\Summoner\SummonerReloadRequest;
use App\Http\Requests\Summoner\SummonerShowRequest;
use App\Http\Resources\ChampionResource;
use App\Http\Resources\Riot\RiotSummonerSpellResource;
use App\Http\Resources\SummonerResource;
use App\Models\Champion;
use App\Models\RiotItems;
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
use function DeepCopy\deep_copy;

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

		$masteryScore = Http::get(env('RIOT_API_URL').'/lol/champion-mastery/v4/scores/by-summoner/'.$summoner->summoner_id.'?api_key='.env('RIOT_API_KEY'))->body();
		$championsJson = Http::get(env('RIOT_API_URL').'/lol/champion-mastery/v4/champion-masteries/by-summoner/'.$summoner->summoner_id.'?api_key='.env('RIOT_API_KEY'))->json();

		$champions = $this->getChampionsData(collect($championsJson));

		return [
			'champions'    => $champions,
			'masteryScore' => $masteryScore,
		];
	}

	/**
	 * @param  \App\Models\Summoner  $summoner
	 * @param  \App\Http\Requests\Summoner\SummonerInfoIndexRequest  $request
	 * @return \Illuminate\Support\Collection
	 */
	public function matches(Summoner $summoner, SummonerInfoIndexRequest $request): Collection
	{
		$summoner = $this->getSummonerByName($summoner->name);

		$matchesJson = Http::get(env('RIOT_API_URL').'/lol/match/v4/matchlists/by-account/'.$summoner->account_id.'?endIndex='.$request->endIndex.'&beginIndex'.$request->beginIndex.'&api_key='.env('RIOT_API_KEY'))->json();

		return $this->getMatches(collect($matchesJson['matches']));

	}

	/**
	 * @param  \Illuminate\Support\Collection  $matches
	 * @return \Illuminate\Support\Collection
	 */
	protected function getMatches(Collection $matches): Collection
	{
		return $matches->map(function ($item) {
			return [
				'gameId'   => $item['gameId'],
				'gameData' => $this->getMatch($item['gameId'])
			];
		});
	}

	/**
	 * @param  string  $gameId
	 * @return array
	 */
	protected function getMatch(string $gameId): array
	{
		$match = Http::get(env('RIOT_API_URL').'/lol/match/v4/matches/'.$gameId.'?api_key='.env('RIOT_API_KEY'))->json();

		return [
			'gameId'       => $match['gameId'],
			'gameCreation' => $match['gameCreation'],
			'gameDuration' => $match['gameDuration'],
			'seasonId'     => $match['seasonId'],
			'gameVersion'  => $match['gameVersion'],
			'gameMode'     => $match['gameMode'],
			'gameType'     => $match['gameType'],
			'teams'        => $this->getTeams($match['teams'], $match['participants'], $match['participantIdentities'])
		];
	}

	/**
	 * @param  array  $teams
	 * @param  array  $participants
	 * @param  array  $participantIdentities
	 * @return array
	 */
	protected function getTeams(array $teams, array $participants, array $participantIdentities): array
	{
		$teamMaped = [];
		foreach ($teams as $index => $team) {
			$teamMaped[$index] = [
				'teamId'               => $team['teamId'],
				'win'                  => $team['win'],
				'firstBlood'           => $team['firstBlood'],
				'firstTower'           => $team['firstTower'],
				'firstInhibitor'       => $team['firstInhibitor'],
				'firstBaron'           => $team['firstBaron'],
				'firstDragon'          => $team['firstDragon'],
				'firstRiftHerald'      => $team['firstRiftHerald'],
				'towerKills'           => $team['towerKills'],
				'inhibitorKills'       => $team['inhibitorKills'],
				'baronKills'           => $team['baronKills'],
				'dragonKills'          => $team['dragonKills'],
				'vilemawKills'         => $team['vilemawKills'],
				'riftHeraldKills'      => $team['riftHeraldKills'],
				'dominionVictoryScore' => $team['dominionVictoryScore'],
				'bans'                 => $this->getBans(collect($team['bans'])),
				'summoners'            => $this->getSummoners($team['teamId'], collect($participants),
					collect($participantIdentities))
			];
		}
		return $teamMaped;
	}

	/**
	 * @param  string  $teamId
	 * @param  \Illuminate\Support\Collection  $participants
	 * @param  \Illuminate\Support\Collection  $participantIdentities
	 * @return array
	 */
	protected function getSummoners(
		string $teamId,
		Collection $participants,
		Collection $participantIdentities
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
					$item['championId'])->first()),
				'spell1'        => new RiotSummonerSpellResource(RiotSummonerSpell::where('key',
					$item['spell1Id'])->first()),
				'spell2'        => new RiotSummonerSpellResource(RiotSummonerSpell::where('key',
					$item['spell2Id'])->first()),
				'stats'         => $this->getStats($item['stats']),
				'timeline'      => $item['timeline'],
				'lane'          => $item['timeline']['lane'],
				'summoner'      => $this->getSummoner($item, $participantIdentities)
			];
			$index++;
		}
		return $summoners;
	}

	/**
	 * @param $participant
	 * @param  \Illuminate\Support\Collection  $participantIdentities
	 * @return array
	 */
	protected function getSummoner($participant, Collection $participantIdentities): array
	{
		$item = $participantIdentities->firstWhere('participantId', '=', $participant['participantId']);
		return [
			'name'        => $item['player']['summonerName'],
			'profileIcon' => RiotSummonerIcon::where('name', $item['player']['profileIcon'])->first()->image,
			'summonerId'  => $item['player']['summonerId'],
		];
	}

	/**
	 * @param $item
	 * @return array
	 */
	protected function getStats($item): array
	{
		return [
			'assists'                         => $item['assists'],
			'champLevel'                      => $item['champLevel'],
			'combatPlayerScore'               => $item['combatPlayerScore'],
			'damageDealtToObjectives'         => $item['damageDealtToObjectives'],
			'damageDealtToTurrets'            => $item['damageDealtToTurrets'],
			'damageSelfMitigated'             => $item['damageSelfMitigated'],
			'deaths'                          => $item['deaths'],
			'doubleKills'                     => $item['doubleKills'],
			'firstBloodAssist'                => $item['firstBloodAssist'],
			//'firstInhibitorAssist'            => $item['firstInhibitorAssist'],
			//'firstInhibitorKill'              => $item['firstInhibitorKill'],
			'firstTowerAssist'                => $item['firstTowerAssist'],
			'firstTowerKill'                  => $item['firstTowerKill'],
			'goldEarned'                      => $item['goldEarned'],
			'goldSpent'                       => $item['goldSpent'],
			'inhibitorKills'                  => $item['inhibitorKills'],
			'item0'                           => RiotItems::where('item_id',
				$item['item0'])->exists() ? RiotItems::where('item_id', $item['item0'])->first()->image : '',
			'item1'                           => RiotItems::where('item_id',
				$item['item1'])->exists() ? RiotItems::where('item_id', $item['item1'])->first()->image : '',
			'item2'                           => RiotItems::where('item_id',
				$item['item2'])->exists() ? RiotItems::where('item_id', $item['item2'])->first()->image : '',
			'item3'                           => RiotItems::where('item_id',
				$item['item3'])->exists() ? RiotItems::where('item_id', $item['item3'])->first()->image : '',
			'item4'                           => RiotItems::where('item_id',
				$item['item4'])->exists() ? RiotItems::where('item_id', $item['item4'])->first()->image : '',
			'item5'                           => RiotItems::where('item_id',
				$item['item5'])->exists() ? RiotItems::where('item_id', $item['item5'])->first()->image : '',
			'item6'                           => RiotItems::where('item_id',
				$item['item6'])->exists() ? RiotItems::where('item_id', $item['item6'])->first()->image : '',
			'killingSprees'                   => $item['killingSprees'],
			'kills'                           => $item['kills'],
			'largestCriticalStrike'           => $item['largestCriticalStrike'],
			'largestKillingSpree'             => $item['largestKillingSpree'],
			'largestMultiKill'                => $item['largestMultiKill'],
			'longestTimeSpentLiving'          => $item['longestTimeSpentLiving'],
			'magicDamageDealt'                => $item['magicDamageDealt'],
			'magicDamageDealtToChampions'     => $item['magicDamageDealtToChampions'],
			'magicalDamageTaken'              => $item['magicalDamageTaken'],
			'neutralMinionsKilled'            => $item['neutralMinionsKilled'],
			'neutralMinionsKilledEnemyJungle' => $item['neutralMinionsKilledEnemyJungle'],
			'neutralMinionsKilledTeamJungle'  => $item['neutralMinionsKilledTeamJungle'],
			'objectivePlayerScore'            => $item['objectivePlayerScore'],
			'participantId'                   => $item['participantId'],
			'pentaKills'                      => $item['pentaKills'],
			'perk0'                           => $item['perk0'],
			'perk0Var1'                       => $item['perk0Var1'],
			'perk0Var2'                       => $item['perk0Var2'],
			'perk0Var3'                       => $item['perk0Var3'],
			'perk1'                           => $item['perk1'],
			'perk1Var1'                       => $item['perk1Var1'],
			'perk1Var2'                       => $item['perk1Var2'],
			'perk1Var3'                       => $item['perk1Var3'],
			'perk2'                           => $item['perk2'],
			'perk2Var1'                       => $item['perk2Var1'],
			'perk2Var2'                       => $item['perk2Var2'],
			'perk2Var3'                       => $item['perk2Var3'],
			'perk3'                           => $item['perk3'],
			'perk3Var1'                       => $item['perk3Var1'],
			'perk3Var2'                       => $item['perk3Var2'],
			'perk3Var3'                       => $item['perk3Var3'],
			'perk4'                           => $item['perk4'],
			'perk4Var1'                       => $item['perk4Var1'],
			'perk4Var2'                       => $item['perk4Var2'],
			'perk4Var3'                       => $item['perk4Var3'],
			'perk5'                           => $item['perk5'],
			'perk5Var1'                       => $item['perk5Var1'],
			'perk5Var2'                       => $item['perk5Var2'],
			'perk5Var3'                       => $item['perk5Var3'],
			'perkPrimaryStyle'                => $item['perkPrimaryStyle'],
			'perkSubStyle'                    => $item['perkSubStyle'],
			'physicalDamageDealt'             => $item['physicalDamageDealt'],
			'physicalDamageDealtToChampions'  => $item['physicalDamageDealtToChampions'],
			'physicalDamageTaken'             => $item['physicalDamageTaken'],
			'playerScore0'                    => $item['playerScore0'],
			'playerScore1'                    => $item['playerScore1'],
			'playerScore2'                    => $item['playerScore2'],
			'playerScore3'                    => $item['playerScore3'],
			'playerScore4'                    => $item['playerScore4'],
			'playerScore5'                    => $item['playerScore5'],
			'playerScore6'                    => $item['playerScore6'],
			'playerScore7'                    => $item['playerScore7'],
			'playerScore8'                    => $item['playerScore8'],
			'playerScore9'                    => $item['playerScore9'],
			'sightWardsBoughtInGame'          => $item['sightWardsBoughtInGame'],
			'statPerk0'                       => $item['statPerk0'],
			'statPerk1'                       => $item['statPerk1'],
			'statPerk2'                       => $item['statPerk2'],
			'timeCCingOthers'                 => $item['timeCCingOthers'],
			'totalDamageDealt'                => $item['totalDamageDealt'],
			'totalDamageDealtToChampions'     => $item['totalDamageDealtToChampions'],
			'totalDamageTaken'                => $item['totalDamageTaken'],
			'totalHeal'                       => $item['totalHeal'],
			'totalMinionsKilled'              => $item['totalMinionsKilled'],
			'totalPlayerScore'                => $item['totalPlayerScore'],
			'totalScoreRank'                  => $item['totalScoreRank'],
			'totalTimeCrowdControlDealt'      => $item['totalTimeCrowdControlDealt'],
			'totalUnitsHealed'                => $item['totalUnitsHealed'],
			'tripleKills'                     => $item['tripleKills'],
			'trueDamageDealt'                 => $item['trueDamageDealt'],
			'trueDamageDealtToChampions'      => $item['trueDamageDealtToChampions'],
			'trueDamageTaken'                 => $item['trueDamageTaken'],
			'turretKills'                     => $item['turretKills'],
			'unrealKills'                     => $item['unrealKills'],
			'visionScore'                     => $item['visionScore'],
			'visionWardsBoughtInGame'         => $item['visionWardsBoughtInGame'],
			'wardsKilled'                     => $item['wardsKilled'],
			'wardsPlaced'                     => $item['wardsPlaced'],
			'win'                             => $item['win'],
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
					$item['championId'])->first()),
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
					$item['championId'])->first()),
				'championPoints'               => $item['championPoints'],
				'chestGranted'                 => $item['chestGranted'],
				'championPointsUntilNextLevel' => $item['championPointsUntilNextLevel']
			];
		});
	}
}
