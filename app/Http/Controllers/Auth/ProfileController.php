<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ProfileIndexRequest;
use App\Http\Requests\Auth\ProfileMainSummonerRequest;
use App\Http\Requests\Auth\ProfileUpdateBranchesRequest;
use App\Http\Requests\Auth\ProfileUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\Summoner;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
			'summoners', 'userGroups', 'mainSummoner', 'image', 'thumbnail', 'memberShip',
			'memberShip.branches' => function ($query) {
				$query->whereNull('branch_member_ship.wanted_to_leave_at');
			},
		])->find($userId));
	}

	public function update(ProfileUpdateRequest $request)
	{
		$user = User::find(Auth::id());
		$originalUser = deep_copy($user);

		if (!$user) {
			return response(null, Response::HTTP_UNAUTHORIZED);
		}

		$user->email = $request->email;

		if ($request->password && $request->passwordRepeat) {
			if ($request->password !== $request->passwordRepeat) {
				return response()->json([
					"message" => "Die Passwörter stimmen nicht überein."
				], Response::HTTP_UNPROCESSABLE_ENTITY);
			}
			$user->password = Hash::make($request->password);
		}

		$user->save();

		if ($request->phone) {
			$user->memberShip->phone = $request->phone;
			$user->memberShip->save();
		}

		if ($originalUser->email !== $user->email) {
			$user->wants_email_notification = false;
			$user->email_verified_at = null;
			$user->save();
			$user->sendEmailVerificationNotification();
		}

		return response()->json([
			'message' => 'Das Profil wurde erfolgreich bearbeitet.',
		], Response::HTTP_OK);
	}

	/**
	 * @param  \App\Http\Requests\Auth\ProfileUpdateBranchesRequest  $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function updateBranches(ProfileUpdateBranchesRequest $request): JsonResponse
	{
		$user = User::where("id", Auth::id())->with([
			'memberShip', 'memberShip.branches' => function ($query) {
				$query->whereNull('branch_member_ship.wanted_to_leave_at');
			},
		])->first();
		/*todo filtern wanted_to_leave_at oder so weil bug ist da ...*/
		/*$originalUser = deep_copy($user);*/
		$newBranches = $request->branchIds;
		$originalBranches = collect(collect($user->memberShip->branches)->map(function ($item) {
			return $item->id;
		}));

		$toRemove = $originalBranches->diff($newBranches);
		$toAdd = collect($newBranches)->diff($originalBranches);

		if (
			DB::table('branch_member_ship')
				->where('member_ship_id', $user->memberShip->id)
				->whereNull('deleted_at')
				->whereNotNull('wanted_to_leave_at')
				->whereIn('branch_id', $toAdd)->exists()
		) {
			DB::table('branch_member_ship')
				->where('member_ship_id', $user->memberShip->id)
				->whereNull('deleted_at')
				->whereNotNull('wanted_to_leave_at')
				->whereIn('branch_id', $toAdd)->update(['wanted_to_leave_at' => null]);
		} else {
			$user->memberShip->branches()->attach($toAdd);
		}
		if (count($toRemove) > 0) {
			DB::table('branch_member_ship')
				->where('member_ship_id', $user->memberShip->id)
				->whereIn('branch_id', $toRemove)
				->update(['wanted_to_leave_at' => now()]);
		}

		$user->fresh();
		$user->load([
			'summoners', 'userGroups', 'mainSummoner', 'image', 'thumbnail', 'memberShip',
			'memberShip.branches' => function ($query) {
				$query->whereNull('branch_member_ship.wanted_to_leave_at');
			},
		]);

		return response()->json([
			'message' => 'Die Sparten wurden erfolgreich bearbeitet.',
			'data'    => new UserResource($user),
		], Response::HTTP_OK);

		/*return response()->json([
			'message' => 'Das Profil wurde erfolgreich bearbeitet.',
			'data' => new UserResource($user),
		], Response::HTTP_OK);*/
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
