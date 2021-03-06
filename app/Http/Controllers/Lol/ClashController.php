<?php

namespace App\Http\Controllers\Lol;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Clash\ClashIndexRequest;
use App\Http\Requests\Clash\ClashTeamCreateRequest;
use App\Http\Requests\Clash\ClashTeamDeleteRequest;
use App\Http\Requests\Clash\ClashTeamUpdateRequest;
use App\Http\Resources\ClashTeamResource;
use App\Models\ClashMember;
use App\Models\ClashTeam;
use App\Models\ClashTeamRole;
use App\Models\Summoner;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
			'clashMembers.summoner.leagueEntries.queueType',
			'clashMembers.user',
			'clashMembers' => function ($query) {
				$query->orderBy('clash_team_role_id');
			},
			'leader'
		])->get();

		return ClashTeamResource::collection($clashTeams);
	}

	/**
	 * @param  ClashTeamCreateRequest  $request
	 * @return JsonResponse
	 */
	public function create(ClashTeamCreateRequest $request): JsonResponse
	{
		ClashTeam::create([
			'name'      => $request->name,
			'leader_id' => Auth::id()
		]);

		return response()->json([
			'message' => 'Das Clashteam wurde erfolgreich erstellt!'
		], Response::HTTP_CREATED);
	}

	/**
	 * @param  ClashTeam  $clashTeam
	 * @param  ClashTeamUpdateRequest  $request
	 * @return JsonResponse
	 */
	public function update(ClashTeamUpdateRequest $request, ClashTeam $clashTeam): JsonResponse
	{
		$clashTeam->update([
			'name'      => $request->name,
			'leader_id' => $request->leaderId
		]);

		if (count($request->deletedMemberIds) > 0) {
			ClashMember::destroy($request->deletedMemberIds);
		}

		if (count($request->newMembers) > 0) {
			foreach ($request->newMembers as $newMember) {
				$roleId = $this->getRoleId($newMember['role']);
				$summonerId = $this->getSummonerId($newMember['userId']);
				$clashTeam->clashMembers()->create([
					'user_id'            => $newMember['userId'],
					'summoner_id'        => $summonerId,
					'clash_team_role_id' => $roleId,
					'is_active'          => true,
				]);
			}
		}

		return response()->json([
			'message' => 'Das Clashteam wurde erfolgreich bearbeitet!'
		], Response::HTTP_CREATED);
	}

	/**
	 * @param  ClashTeam  $clashTeam
	 * @param  ClashTeamDeleteRequest  $request
	 * @return JsonResponse
	 */
	public function delete(ClashTeam $clashTeam, ClashTeamDeleteRequest $request): JsonResponse
	{
		try {
			$clashTeam->delete();
		} catch (Exception $exception) {
			return response()->json([
				'message' => 'Das Clashteam konnte nicht erfolgreich gelöscht werden!'
			], Response::HTTP_INTERNAL_SERVER_ERROR);
		}

		return response()->json([
			'message' => 'Das Clashteam wurde erfolgreich gelöscht!'
		], Response::HTTP_CREATED);
	}

	/**
	 * @param $roleName
	 * @return mixed
	 */
	protected function getRoleId($roleName)
	{
		return ClashTeamRole::where('name', $roleName)->first()->id;
	}

	/**
	 * @param $userId
	 * @return mixed
	 */
	protected function getSummonerId($userId)
	{
		return Summoner::where('main_user_id', $userId)->first()->id;
	}
}
