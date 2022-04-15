<?php

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Http\Requests\Member\BranchMemberIndexRequest;
use App\Http\Resources\UserResource;
use App\Mail\BranchMemberShipAcceptMail;
use App\Mail\BranchMemberShipRejectMail;
use App\Models\BranchUserMemberShip;
use App\Models\User;
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
			$query->whereExists(function ($query) {
				$query->where('branch_id', 1)->whereNotNull('activated_at');
			})->whereNull('activated_at');
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

		$user = User::query()->find($branchUserMemberShip->user_id);

		/*todo Email ist noch falsch und mit Notification ersetzen*/
		Mail::to($user->email)->send(new BranchMemberShipAcceptMail($user));

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

		$user = User::query()->find($branchUserMemberShip->user_id);

		$branchUserMemberShip->exported_at = null;
		$branchUserMemberShip->save();
		$branchUserMemberShip->delete();

		/*todo Email ist noch falsch und mit Notification ersetzen*/
		Mail::to($user->email)->send(new BranchMemberShipRejectMail($user));

		return response()->json([
			"message" => "Der Spartenbeitrit wurde erfolgreich abgelehnt",
		], Response::HTTP_OK);
	}
}
