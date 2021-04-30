<?php

namespace App\Http\Controllers\Auth;

use App\Events\BirthdayChanged;
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
use Illuminate\Support\Facades\Log;
use function DeepCopy\deep_copy;


class ProfileController extends Controller
{
	/**
	 * @param  ProfileIndexRequest  $request
	 * @return UserResource
	 */
	public function index(ProfileIndexRequest $request): UserResource
	{
		$userId = Auth::id();

		return new UserResource(User::with([
			'summoners', 'userGroups', 'mainSummoner', 'image', 'thumbnail'
		])->find($userId));
	}

	public function update(ProfileUpdateRequest $request)
	{
		$user = User::find(Auth::id());
		$originalUser = deep_copy($user);

		if (!$user) {
			return response(null, Response::HTTP_UNAUTHORIZED);
		}

		$user->first_name = $request->firstName;
		$user->last_name = $request->lastName;
		$user->username = $request->username;
		$user->email = $request->email;
		$user->wants_email_notification = $request->wantsEmailNotification;
		$user->birthday = $request->birthday;

		if ($request->password && $request->passwordRepeat) {
			if ($request->password !== $request->passwordRepeat) {
				return response()->json([
					"message" => "Die Passwörter stimmen nicht überein."
				], Response::HTTP_UNPROCESSABLE_ENTITY);
			}
			$user->password = Hash::make($request->password);
		}

		$user->save();

		if ($originalUser->email !== $user->email) {
			$user->wants_email_notification = false;
			$user->email_verified_at = null;
			$user->save();
			$user->sendEmailVerificationNotification();
		}

		if ($user->birthday !== $originalUser->birthday) {
			event(new BirthdayChanged($user));
		}

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
