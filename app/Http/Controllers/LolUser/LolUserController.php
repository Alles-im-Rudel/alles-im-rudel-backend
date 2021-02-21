<?php

namespace App\Http\Controllers\LolUser;

use App\Http\Controllers\BaseController;
use App\Http\Requests\LolUser\LolUserDeleteRequest;
use App\Http\Requests\LolUser\LolUserIndexRequest;
use App\Http\Requests\LolUser\LolUserShowRequest;
use App\Http\Requests\LolUser\LolUserSyncUserRequest;
use App\Http\Requests\LolUser\LolUserUpdateRequest;
use App\Http\Resources\SummonerResource;
use App\Http\Resources\UserResource;
use App\Models\Summoner;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class LolUserController extends BaseController
{

	/**
	 * UserController constructor.
	 */
	public function __construct()
	{
		$this->builder = Summoner::query();
		$this->tableName = 'lol_users';
		$this->availableOrderByFields = [
			'name',
		];
		$this->searchFields = [
			'name',
		];
	}


	/**
	 * @param  LolUserIndexRequest  $request
	 * @return AnonymousResourceCollection
	 */
	public function index(LolUserIndexRequest $request): AnonymousResourceCollection
	{
		$this->orderByJson($request->sortBy);
		$this->onlyTrashed($request->onlyTrashed);
		$this->search("%{$request->search}%")
			->withCount('roles', 'permissions', 'userGroups');

		return SummonerResource::collection($this->paginate($request->perPage, $request->page));
	}

	/**
	 * @param  LolUserShowRequest  $request
	 * @param  Summoner  $lolUser
	 * @return UserResource
	 */
	public function show(LolUserShowRequest $request, Summoner $lolUser): UserResource
	{
		return new UserResource($lolUser);
	}

	/**
	 * @param  LolUserUpdateRequest  $request
	 * @param  Summoner  $lolUser
	 * @return JsonResponse
	 */
	public function update(LolUserUpdateRequest $request, Summoner $lolUser): JsonResponse
	{
		$lolUser->update([
			'name'         => $request->name,
			'activated_at' => $request->isActive ? now() : null,
		]);


		return response()->json([
			'message' => 'Der Lol Benutzer wurde erfolgreich gelöscht.',
			'lolUser' => new SummonerResource($lolUser)
		], Response::HTTP_OK);
	}

	/**
	 * @param  LolUserSyncUserRequest  $request
	 * @param  Summoner  $lolUser
	 * @return JsonResponse
	 */
	public function syncUsers(LolUserSyncUserRequest $request, Summoner $lolUser): JsonResponse
	{
		$lolUser->users()->sync($request->userIds);
		return response()->json([
			'message' => 'Die Benutzer wurden erfolgreich mit dem Lol Account verknüpft'
		], Response::HTTP_OK);
	}

	/**
	 * @param  LolUserDeleteRequest  $request
	 * @param  Summoner  $lolUser
	 * @return JsonResponse
	 */
	public function delete(LolUserDeleteRequest $request, Summoner $lolUser): JsonResponse
	{
		try {
			$lolUser->delete();
		} catch (Exception $e) {
			return response()->json([
				'message' => 'Der Lol Account konnte nicht gelöscht werde!.',
			], Response::HTTP_OK);
		}

		return response()->json([
			'message' => 'Der Lol Account wurde erfolgreich gelöscht.',
		], Response::HTTP_OK);
	}
}
