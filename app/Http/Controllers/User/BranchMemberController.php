<?php

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Http\Requests\Member\BranchMemberIndexRequest;
use App\Http\Resources\UserResource;
use App\Models\BranchUserMemberShip;
use App\Models\User;
use App\Models\UserGroup;
use App\Notifications\BranchMembershipAcceptNotification;
use App\Notifications\BranchMembershipRejectNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class BranchMemberController extends Controller
{

	/**
	 * @param  \App\Http\Requests\Member\BranchMemberIndexRequest  $request
	 * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
	 */
	public function index(BranchMemberIndexRequest $request): AnonymousResourceCollection
	{
		$users = User::with([
			'country',
			'bankAccount',
			'branchUserMemberShips' => function ($query) {
				$query->whereNull('activated_at');
			},
			'branchUserMemberShips.branch'
		])->whereHas('branchUserMemberShips', function ($query) {
			$query->where('branch_id', 1)->whereNotNull('activated_at');
		})->whereHas('branchUserMemberShips', function ($query) {
			$query->whereNull('activated_at');
		});

		return UserResource::collection($users->paginate(9, '*', $request->page, $request->page));
	}

	/**
	 * @param  \App\Models\BranchUserMemberShip  $branchUserMemberShip
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function accept(BranchUserMemberShip $branchUserMemberShip): JsonResponse
	{
		if (!Auth::user()->can('members.mamage')) {
			return response()->json(["msg" => "Keine Berechtigung"], 403);
		}

		$branchUserMemberShip->activated_at = now();
		$branchUserMemberShip->save();


        /** @var User $user */
		$user = User::query()->find($branchUserMemberShip->user_id);

		if ($branchUserMemberShip->branch_id === 1) {
			$user->userGroups()->attach(UserGroup::MEMBER_ID);
		}
		if ($branchUserMemberShip->branch_id === 2) {
			$user->userGroups()->attach(UserGroup::AIRSOFT_MEMBER_ID);
		}
		if ($branchUserMemberShip->branch_id === 3) {
			$user->userGroups()->attach(UserGroup::E_SPORTS_MEMBER_ID);
		}

        $user->notify(new BranchMembershipAcceptNotification());

		return response()->json([
			"message" => "Der Spartenbeitrit wurde erfolgreich bestätigt",
		], Response::HTTP_OK);
	}

	/**
	 * @param  \App\Models\BranchUserMemberShip  $branchUserMemberShip
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function reject(BranchUserMemberShip $branchUserMemberShip): JsonResponse
	{
		if (!Auth::user()->can('members.mamage')) {
			return response()->json(["msg" => "Keine Berechtigung"], 403);
		}

        /** @var User $user */
		$user = User::query()->find($branchUserMemberShip->user_id);

		$branchUserMemberShip->exported_at = null;
		$branchUserMemberShip->save();
		$branchUserMemberShip->delete();

        $user->notify(new BranchMembershipRejectNotification());

		return response()->json([
			"message" => "Der Spartenbeitrit wurde erfolgreich abgelehnt",
		], Response::HTTP_OK);
	}
}
