<?php

namespace App\Http\Controllers\UserGroup;

use App\Http\Controllers\BaseController;
use App\Http\Requests\User\UserShowRequest;
use App\Http\Requests\UserGroup\UserGroupAllRequest;
use App\Http\Requests\UserGroup\UserGroupDeleteRequest;
use App\Http\Requests\UserGroup\UserGroupIndexRequest;
use App\Http\Requests\UserGroup\UserGroupSyncPermissionRequest;
use App\Http\Requests\UserGroup\UserGroupSyncUserRequest;
use App\Http\Requests\UserGroup\UserGroupUpdateRequest;
use App\Http\Resources\UserGroupResource;
use App\Models\UserGroup;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class UserGroupController extends BaseController
{
	/**
	 * UserController constructor.
	 */
	public function __construct()
	{
		$this->builder = UserGroup::query();
		$this->tableName = 'user-groups';
		$this->availableOrderByFields = [
			'display_name',
			'level_id',
			'updated_at',
		];
		$this->searchFields = [
			'display_name'
		];
	}

	/**
	 * @param  UserGroupIndexRequest  $request
	 * @return AnonymousResourceCollection
	 */
	public function index(UserGroupIndexRequest $request): AnonymousResourceCollection
	{
		$this->orderByJson($request->sortBy);
		$this->onlyTrashed($request->onlyTrashed);
		$this->search("%{$request->search}%")
			->withCount('roles', 'permissions', 'users');

		return UserGroupResource::collection($this->paginate($request->perPage, $request->page));
	}

	/**
	 * @param  UserGroupAllRequest  $request
	 * @return AnonymousResourceCollection
	 */
	public function all(UserGroupAllRequest $request): AnonymousResourceCollection
	{
		$userGroups = UserGroup::query();
		if ($request->withOutUserGroupIds && count($request->withOutUserGroupIds) > 0) {
			$userGroups->whereNotIn('id', $request->withOutUserGroupIds);
		}
		return UserGroupResource::collection($userGroups->get());
	}

	/**
	 * @param  UserShowRequest  $request
	 * @param  UserGroup  $userGroup
	 * @return UserGroupResource
	 */
	public function show(UserShowRequest $request, UserGroup $userGroup): UserGroupResource
	{
		$userGroup->loadMissing('permissions', 'roles', 'users');
		return new UserGroupResource($userGroup);
	}

	/**
	 * @param  UserGroupUpdateRequest  $request
	 * @param  UserGroup  $userGroup
	 * @return JsonResponse
	 */
	public function update(UserGroupUpdateRequest $request, UserGroup $userGroup): JsonResponse
	{
		$userGroup->update([
			'level_id'     => $request->levelId,
			'display_name' => $request->displayName,
			'color'        => $request->color,
			'description'  => $request->description,
		]);

		$userGroup->loadMissing('permissions', 'roles', 'users');

		return response()->json([
			'message'   => 'Die Benutzergruppe wurde bearbeitet gelöscht.',
			'userGroup' => new UserGroupResource($userGroup)
		], Response::HTTP_OK);
	}

	/**
	 * @param  UserGroupSyncPermissionRequest  $request
	 * @param  UserGroup  $userGroup
	 * @return JsonResponse
	 */
	public function syncPermissions(UserGroupSyncPermissionRequest $request, UserGroup $userGroup): JsonResponse
	{
		$userGroup->syncPermissions($request->permissionIds);
		return response()->json([
			'message' => 'Die Berechtigungen wurden erfolgreich mit der Benutzergruppe verknüpft'
		], Response::HTTP_OK);
	}

	/**
	 * @param  UserGroupSyncUserRequest  $request
	 * @param  UserGroup  $userGroup
	 * @return JsonResponse
	 */
	public function syncUsers(UserGroupSyncUserRequest $request, UserGroup $userGroup): JsonResponse
	{
		$userGroup->users()->sync($request->userIds);
		return response()->json([
			'message' => 'Die Berechtigungen wurden erfolgreich mit dem Benutzer verknüpft'
		], Response::HTTP_OK);
	}

	/**
	 * @param  UserGroupDeleteRequest  $request
	 * @param  UserGroup  $userGroup
	 * @return JsonResponse
	 */
	public function delete(UserGroupDeleteRequest $request, UserGroup $userGroup): JsonResponse
	{
		try {
			$userGroup->delete();
		} catch (Exception $e) {
			return response()->json([
				'message' => 'Die Benutzergruppe konnte nicht gelöscht werde!.',
			], Response::HTTP_OK);
		}

		return response()->json([
			'message' => 'Die Benutzergruppe wurde erfolgreich gelöscht.',
		], Response::HTTP_OK);
	}
}
