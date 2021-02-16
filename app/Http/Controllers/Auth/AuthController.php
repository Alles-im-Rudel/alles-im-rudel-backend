<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Auth\AuthRegisterRequest;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
	/**
	 * @return JsonResponse
	 */
	public function index(): JsonResponse
	{
		$user = Auth::guard('api')->user();
		if ($user) {

			$permissions = collect($user->getAllPermissions());

			foreach ($user->userGroups as $userGroup) {
				foreach ($userGroup->getAllPermissions() as $permission) {
					if (!$permissions->contains('id', $permission->id)) {
						$permissions = $permissions->merge([$permission]);
					}
				}
			}
			return response()->json([
				'user'        => new UserResource($user),
				'permissions' => PermissionResource::collection($permissions)
			]);
		}
		return response()->json([
			'message' => 'Kein Auth User!'
		], Response::HTTP_FORBIDDEN);
	}

	/**
	 * @param  AuthLoginRequest  $request
	 * @return JsonResponse
	 */
	public function login(AuthLoginRequest $request): JsonResponse
	{
		$user = User::where('email', '=', $request->email)->where('activated_at', '<>', null)->exists();

		if (!$user) {
			return response()->json([
				"message" => 'Kein Valider User'
			], Response::HTTP_UNAUTHORIZED);
		}

		$http = new Client();

		try {
			$tokens = $http->post(env('OAUTH2_AUTH_URL'), [
				'form_params' => [
					'grant_type'    => 'password',
					'client_id'     => (string) env('PASSPORT_CLIENT_ID'),
					'client_secret' => (string) env('PASSPORT_CLIENT_SECRET'),
					'username'      => $request->email,
					'password'      => $request->password
				],
			]);
		} catch (GuzzleException $exception) {
			return response()->json([
				"message" => 'Passport Fehler',
			], Response::HTTP_UNAUTHORIZED);
		}
		try {
			$decodedTokens = json_decode($tokens->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
		} catch (Exception $exception) {
			return response()->json([
				"message" => __('auth.failed')
			], Response::HTTP_INTERNAL_SERVER_ERROR);
		}
		$user = User::where('email', '=', $request->email)->where('activated_at', '<>', null)->first();

		$permissions = collect($user->getAllPermissions());

		foreach ($user->userGroups as $userGroup) {
			foreach ($userGroup->getAllPermissions() as $permission) {
				if (!$permissions->contains('id', $permission->id)) {
					$permissions = $permissions->merge([$permission]);
				}
			}
		}

		return response()->json([
			'user'        => new UserResource($user),
			'tokens'      => $decodedTokens,
			'permissions' => PermissionResource::collection($permissions)
		]);
	}

	/**
	 * @param  AuthRegisterRequest  $request
	 * @return JsonResponse
	 */
	public function register(AuthRegisterRequest $request): JsonResponse
	{
		$now = now();
		$user = User::create([
			'password'          => Hash::make($request->password),
			'email'             => $request->email,
			'username'          => $request->username,
			'email_verified_at' => $now,
			'activated_at'      => $now
		]);

		$http = new Client();

		try {
			$tokens = $http->post(env('OAUTH2_AUTH_URL'), [
				'form_params' => [
					'grant_type'    => 'password',
					'client_id'     => (string) env('PASSPORT_CLIENT_ID'),
					'client_secret' => (string) env('PASSPORT_CLIENT_SECRET'),
					'username'      => $request->email,
					'password'      => $request->password
				],
			]);
		} catch (GuzzleException $exception) {
			return response()->json([
				"message" => __('auth.failed')
			], Response::HTTP_UNAUTHORIZED);
		}
		try {
			$decodedTokens = json_decode($tokens->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
		} catch (Exception $exception) {
			return response()->json([
				"message" => __('auth.failed')
			], Response::HTTP_INTERNAL_SERVER_ERROR);
		}

		$permissions = collect($user->getAllPermissions());

		foreach ($user->userGroups as $userGroup) {
			foreach ($userGroup->getAllPermissions() as $permission) {
				if (!$permissions->contains('id', $permission->id)) {
					$permissions = $permissions->merge([$permission]);
				}
			}
		}

		return response()->json([
			'user'        => new UserResource($user),
			'tokens'      => $decodedTokens,
			'permissions' => PermissionResource::collection($permissions)
		]);
	}

	/**
	 * @return JsonResponse
	 */
	public function logout(): JsonResponse
	{
		$user = Auth::user();

		if ($user) {
			$user->tokens()->delete();
		}

		return response()->json([
			'message' => 'Erfolgreich ausgeloggt.'
		], Response::HTTP_OK);
	}
}
