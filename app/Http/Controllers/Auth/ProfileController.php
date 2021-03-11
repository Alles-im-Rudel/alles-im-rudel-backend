<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ProfileIndexRequest;
use App\Http\Requests\Auth\ProfileMainSummonerRequest;
use App\Http\Requests\Auth\ProfileUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\Summoner;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class ProfileController extends Controller
{
	/**
	 * @param  ProfileIndexRequest  $request
	 * @return UserResource
	 */
	public function index(ProfileIndexRequest $request): UserResource
	{
		$userId = Auth::id();

		return new UserResource(User::with(['summoners', 'userGroups', 'mainSummoner', 'image', 'thumbnail'])->find($userId));
	}

	public function update(ProfileUpdateRequest $request)
	{
		$user = Auth::user();

		if (!$user) {
			return response(null, Response::HTTP_UNAUTHORIZED);
		}

		$userData = [
			'first_name' => $request->firstName,
			'last_name'  => $request->lastName,
			'username'   => $request->username,
			'email'      => $request->email
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

		return response()->json([
			'message' => 'Das Profil wurde erfolgreich bearbeitet.',
		], Response::HTTP_OK);
	}

	public function mainSummoner(ProfileMainSummonerRequest $request)
	{
		$userId = Auth::id();

		if (!$userId) {
			return response(null, Response::HTTP_UNAUTHORIZED);
		}

		Summoner::where('main_user_id', $userId)->update([
			'main_user_id' => null
		]);

		if ($request->mainSummonerId) {
			Summoner::find($request->mainSummonerId)->update([
				'main_user_id' => $userId
			]);
		}

		return response()->json([
			'message' => 'Das Profil wurde erfolgreich bearbeitet.',
		], Response::HTTP_OK);
	}

	public function delete()
	{

	}
}
