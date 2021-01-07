<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserDeleteRequest;
use App\Http\Requests\User\UserIndexRequest;
use App\Http\Requests\User\UserShowRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class UserController extends Controller
{
	/**
	 * @param  UserIndexRequest  $request
	 * @return AnonymousResourceCollection
	 */
	public function index(UserIndexRequest $request): AnonymousResourceCollection
	{
		$query = User::query();

		if ($request->search) {
			$query->where('first_name', 'LIKE', "%{$request->search}%")
				->where('last_name', 'LIKE', "%{$request->search}%")
				->where('username', 'LIKE', "%{$request->search}%")
				->where('email', 'LIKE', "%{$request->search}%");
		}
		if ($request->sortBy) {
			foreach (json_decode($request->sortBy, true) as $sortBy => $desc) {
				$direction = filter_var($desc, FILTER_VALIDATE_BOOLEAN) === true ? 'DESC' : 'ASC';
				$query->orderBy($sortBy, $direction);
			}
		}
		return UserResource::collection($query->paginate($request->perPage, ['*'], 'page', $request->page));
	}

	/**
	 * @param  UserShowRequest  $request
	 * @param  User  $user
	 * @return UserResource
	 */
	public function show(UserShowRequest $request, User $user): UserResource
	{
		$user->loadMissing('permissions', 'roles');
		return new UserResource($user);
	}

	/**
	 * @param  UserUpdateRequest  $request
	 * @param  User  $user
	 * @return JsonResponse
	 */
	public function update(UserUpdateRequest $request, User $user): JsonResponse
	{
		$user->update([
			'first_name'   => $request->firstName,
			'last_name'    => $request->lastName,
			'username'     => $request->username,
			'email'        => $request->email,
			'activated_at' => $request->isActive ? now() : null,
		]);

		$user->loadMissing('permissions', 'roles');

		return response()->json([
			'message' => 'Der Benutzer wurde erfolgreich gelöscht.',
			'user'    => new UserResource($user)
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
