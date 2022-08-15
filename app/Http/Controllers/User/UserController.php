<?php

namespace App\Http\Controllers\User;

use App\Events\BirthdayChanged;
use App\Http\Controllers\BaseController;
use App\Http\Requests\User\UserAllRequest;
use App\Http\Requests\User\UserDeleteRequest;
use App\Http\Requests\User\UserIndexRequest;
use App\Http\Requests\User\UserShowRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserSyncPermissionRequest;
use App\Http\Requests\User\UserSyncUserGroupRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\Country;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
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
			'salutation',
			'activated_at',
			'updated_at',
		];
		$this->searchFields = [
			'email',
			'first_name',
			'last_name',
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
		$this->whereHas('branchUserMemberShips', $request->branchId);
		$this->search("%{$request->search}%")
			->withCount('roles', 'permissions', 'userGroups', 'thumbnail', 'branchUserMemberShips');
		
		$this->getQuery()->levelScope()->canSee();
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
		$user->loadMissing([
			'permissions',
			'roles',
			'userGroups',
			'thumbnail',
			'image',
			'country',
			'bankAccount.signature',
			'bankAccount.country',
			'branchUserMemberShips.branch',
			'mainSummoner'
		]);
		return new UserResource($user);
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
			'salutation'               => $request->salutation,
			'first_name'               => $request->firstName,
			'last_name'                => $request->lastName,
			'street'                   => $request->street,
			'postcode'                 => $request->postcode,
			'city'                     => $request->city,
			'country_id'               => Country::where("name", $request->country)->first()->id,
			'birthday'                 => $request->birthday,
			'email'                    => $request->email,
			'phone'                    => $request->phone,
			'wants_email_notification' => $user->email_verified_at && $request->wantsEmailNotification,
			'activated_at'             => $request->isActive ? now() : null,
			'level_id'                 => $request->levelId,
		];

		$bankAccountData = [
			'bic'        => $request->bankAccountBic,
			'iban'       => str_replace(' ', '', $request->bankAccountIban),
			'first_name' => $request->bankAccountFirstName,
			'last_name'  => $request->bankAccountLastName,
			'street'     => $request->bankAccountStreet,
			'postcode'   => $request->bankAccountPostcode,
			'city'       => $request->bankAccountCity,
			'country_id' => Country::where("name", $request->bankAccountCountry)->first()->id,
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
		$user->bankAccount()->update($bankAccountData);

		if ($user->birthday !== $originalUser->birthday) {
			event(new BirthdayChanged($user));
		}

		if ($user->email !== $originalUser->email) {
			$user->wants_email_notification = false;
			$user->email_verified_at = null;
			$user->save();
			$user->sendEmailVerificationNotification();
		}

		$user->loadMissing(
			'permissions',
			'roles',
			'userGroups',
			'thumbnail',
			'image',
			'country',
			'bankAccount.signature',
			'bankAccount.country',
			'branchUserMemberShips.branch',
			'mainSummoner'
		);

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

	/**
	 * @param $email
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function checkEmail($email): JsonResponse
	{
		$validator = Validator::make(['email' => $email], [
			'email' => 'required|email|unique:users,email'
		]);

		try {
			$isValid = $validator->validate();
		} catch (ValidationException $exception) {
			$isValid = false;
		}

		return response()->json([
			'isValid' => $isValid
		], Response::HTTP_OK);
	}
}
