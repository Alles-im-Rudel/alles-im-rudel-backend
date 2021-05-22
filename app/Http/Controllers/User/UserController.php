<?php

namespace App\Http\Controllers\User;

use App\Events\BirthdayChanged;
use App\Http\Controllers\BaseController;
use App\Http\Requests\User\UserAllRequest;
use App\Http\Requests\User\UserDeleteRequest;
use App\Http\Requests\User\UserIndexRequest;
use App\Http\Requests\User\UserShowRequest;
use App\Http\Requests\User\UserSyncPermissionRequest;
use App\Http\Requests\User\UserSyncUserGroupRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use function DeepCopy\deep_copy;

class UserController extends BaseController
{

	/**
	 * UserController constructor.
	 */
	public function __construct()
	{
		$this->builder = User::query();
		$this->tableName = 'users';
		$this->availableOrderByFields = [
			'first_name',
			'last_name',
			'email',
			'username',
			'salutation',
			'activated_at',
			'updated_at',
		];
		$this->searchFields = [
			'email',
			'first_name',
			'last_name',
			'username'
		];
	}


	/**
	 * @param  UserIndexRequest  $request
	 * @return AnonymousResourceCollection
	 */
	public function index(UserIndexRequest $request): AnonymousResourceCollection
	{
		$this->orderByJson($request->sortBy);
		$this->onlyTrashed($request->onlyTrashed);
		$this->search("%{$request->search}%")
			->withCount('roles', 'permissions', 'userGroups', 'thumbnail');
		$this->getQuery()->levelScope();
		return UserResource::collection($this->paginate($request->perPage, $request->page));
	}

	/**
	 * @param  UserAllRequest  $request
	 * @return AnonymousResourceCollection
	 */
	public function all(UserAllRequest $request): AnonymousResourceCollection
	{
		$users = User::query();
		if ($request->withOutUserIds && count($request->withOutUserIds) > 0) {
			$users->whereNotIn('id', $request->withOutUserIds);
		}
		return UserResource::collection($users->get());
	}

	/**
	 * @param  UserShowRequest  $request
	 * @param  User  $user
	 * @return UserResource
	 */
	public function show(UserShowRequest $request, User $user): UserResource
	{
		$user->loadMissing('permissions', 'roles', 'userGroups', 'mainSummoner', 'thumbnail', 'image');
		return new UserResource($user);
	}

	/**
	 * @param  Request  $request
	 * @return UserResource|JsonResponse
	 */
	public function showProfile(Request $request)
	{
		if (User::where('username', $request->username)->exists()) {
			$user = User::where('username', $request->username)->with([
				'permissions',
				'roles',
				'userGroups',
				'mainSummoner.leagueEntries.queueType',
				'thumbnail',
				'image'
			])->withCount('posts', 'comments', 'liked')->first();
			return new UserResource($user);
		}
		return response()->json([
			'message' => 'Benutzerprofil nicht gefunden',
		], Response::HTTP_NOT_FOUND);
	}

	/**
	 * @param  UserUpdateRequest  $request
	 * @param  User  $user
	 * @return JsonResponse
	 */
	public function update(UserUpdateRequest $request, User $user): JsonResponse
	{

		$originalUser = deep_copy($user);

		$userData = [
			'first_name'               => $request->firstName,
			'last_name'                => $request->lastName,
			'username'                 => $request->username,
			'email'                    => $request->email,
			'birthday'                 => $request->birthday,
			'level_id'                 => $request->levelId,
			'wants_email_notification' => $request->wantsWmailNotification,
			'activated_at'             => $request->isActive ? now() : null,
		];

		if ($request->password && $request->passwordRepeat) {
			if ($request->password !== $request->passwordRepeat) {
				return response()->json([
					"message" => "Die Passwörter stimmen nicht überein."
				], Response::HTTP_UNPROCESSABLE_ENTITY);
			}
			$userData['password'] = Hash::make($request->password);
		}

		$user->update($userData);

		if ($user->birthday !== $originalUser->birthday) {
			event(new BirthdayChanged($user));
		}

		$user->loadMissing('permissions', 'roles', 'userGroups');

		return response()->json([
			'message' => 'Der Benutzer wurde erfolgreich bearbeitet.',
			'user'    => new UserResource($user)
		], Response::HTTP_OK);
	}

	/**
	 * @param  UserSyncPermissionRequest  $request
	 * @param  User  $user
	 * @return JsonResponse
	 */
	public function syncPermissions(UserSyncPermissionRequest $request, User $user): JsonResponse
	{
		$user->syncPermissions($request->permissionIds);
		return response()->json([
			'message' => 'Die Berechtigungen wurden erfolgreich mit dem Benutzer verknüpft'
		], Response::HTTP_OK);
	}

	/**
	 * @param  UserSyncUserGroupRequest  $request
	 * @param  User  $user
	 * @return JsonResponse
	 */
	public function syncUserGroups(UserSyncUserGroupRequest $request, User $user): JsonResponse
	{
		$user->userGroups()->sync($request->userGroupIds);
		return response()->json([
			'message' => 'Die Berechtigungen wurden erfolgreich mit dem Benutzer verknüpft'
		], Response::HTTP_OK);
	}

	/**
	 * @param  UserDeleteRequest  $request
	 * @param  User  $user
	 * @return JsonResponse
	 */
	public function delete(UserDeleteRequest $request, User $user): JsonResponse
	{
		try {
			$user->delete();
		} catch (Exception $e) {
			return response()->json([
				'message' => 'Der Benutzer konnte nicht gelöscht werde!.',
			], Response::HTTP_OK);
		}

		return response()->json([
			'message' => 'Der Benutzer wurde erfolgreich gelöscht.',
		], Response::HTTP_OK);
	}
}
