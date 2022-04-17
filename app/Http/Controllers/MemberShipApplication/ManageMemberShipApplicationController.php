<?php

namespace App\Http\Controllers\MemberShipApplication;

use App\Events\BirthdayChanged;
use App\Http\Controllers\Controller;
use App\Http\Requests\MemberShipApplication\ManageMemberShipApplicationIndexRequest;
use App\Http\Resources\UserResource;
use App\Models\BankAccount;
use App\Models\BranchUserMemberShip;
use App\Models\User;
use App\Notifications\MembershipAcceptNotification;
use App\Notifications\MembershipRejectNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ManageMemberShipApplicationController extends Controller
{
	/**
	 * @param  \App\Http\Requests\MemberShipApplication\ManageMemberShipApplicationIndexRequest  $request
	 * @return AnonymousResourceCollection
	 */
	public function index(ManageMemberShipApplicationIndexRequest $request): AnonymousResourceCollection
	{
		$users = User::with([
			'country',
			'bankAccount',
			'bankAccount.country',
			'branchUserMemberShips',
			'branchUserMemberShips.branch',
		])->whereHas('branchUserMemberShips', function ($query) {
			$query->where('branch_id', 1)->whereNull('activated_at');
		});

		return UserResource::collection($users->paginate(9, '*', $request->page, $request->page));
	}

	/**
	 * @param  \App\Models\User  $user
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function accept(User $user): JsonResponse
	{
		if (!Auth::user()->can('members.mamage')) {
			return response()->json(["msg" => "Keine Berechtigung"], 403);
		}

		$user->activated_at = now();
		$user->save();

		BranchUserMemberShip::where('user_id', $user->id)->update([
			'activated_at' => now()
		]);

        $user->notify(new MembershipAcceptNotification());
		event(new BirthdayChanged($user));

		return response()->json([
			"message" => "Die Anmeldung wurde erfolgreich bestätigt",
		], Response::HTTP_OK);
	}

	/**
	 * @param  \App\Models\User  $user
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function reject(User $user): JsonResponse
	{
		if (!Auth::user()->can('members.mamage')) {
			return response()->json(["msg" => "Keine Berechtigung"], 403);
		}

        $user->notify(new MembershipRejectNotification());
		$bankAccount = BankAccount::find($user->bank_account_id);

		$user->forceDelete();
		$bankAccount->delete();

		return response()->json([
			"message" => "Die Anmeldung wurde erfolgreich abgelehnt",
		], Response::HTTP_OK);
	}
}
