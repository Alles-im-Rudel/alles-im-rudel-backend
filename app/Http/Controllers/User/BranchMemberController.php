<?php

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Http\Requests\Member\BranchMemberIndexRequest;
use App\Http\Requests\Member\MemberRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Branch;
use App\Models\Country;
use App\Models\Level;
use App\Models\MemberShip;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BranchMemberController extends Controller
{

	/**
	 * @param  \App\Http\Requests\Member\BranchMemberIndexRequest  $request
	 * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
	 */
	public function index(BranchMemberIndexRequest $request): AnonymousResourceCollection
	{
		$users = User::with([
			'memberShip', 'memberShip.country', 'memberShip.branches' => function ($query) {
				return $query->whereNull('branch_member_ship.activated_at');
			},
		])->whereHas('memberShip', function ($query) {
			$query->whereHas('branches', function ($query) {
				return $query->whereNull('branch_member_ship.activated_at');
			});
		});

		return UserResource::collection($users->paginate(9, '*', $request->page, $request->page));
	}


	/**
	 * @param  \App\Models\User  $user
	 * @return \App\Http\Resources\UserResource
	 */
	public function show(User $user): UserResource
	{
		$user->loadMissing('memberShip', 'memberShip.branches', 'memberShip.country');

		return new UserResource($user);
	}


	/**
	 * @param  \App\Models\User  $user
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function accept(User $user, Request $request): JsonResponse
	{
		if (!Auth::user()->can('members.mamage')) {
			return response()->json(["msg" => "Keine Berechtigung"], 403);
		}

		DB::table('branch_member_ship')
			->where('id', $request->pivotId)
			->update(['activated_at' => now()]);

		return response()->json([
			"message" => "Der Spartenbeitrit wurde erfolgreich bestätigt",
		], Response::HTTP_OK);
	}

	/**
	 * @param  \App\Models\User  $user
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function reject(User $user, Request $request): JsonResponse
	{
		if (!Auth::user()->can('members.mamage')) {
			return response()->json(["msg" => "Keine Berechtigung"], 403);
		}

		DB::table('branch_member_ship')
			->where('id', $request->pivotId)
			->update(['deleted_at' => now()]);

		return response()->json([
			"message" => "Der Spartenbeitrit wurde erfolgreich abgelehnt",
		], Response::HTTP_OK);
	}

	/**
	 * @param  \App\Http\Requests\Member\MemberRegisterRequest  $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function register(MemberRegisterRequest $request): JsonResponse
	{
		$user = User::create([
			'first_name'               => $request->firstName,
			'last_name'                => $request->lastName,
			'email'                    => $request->email,
			'birthday'                 => $request->birthday,
			'wants_email_notification' => $request->wantsEmailNotification,
			'password'                 => $request->password,
			'level_id'                 => Level::GUEST,
		]);

		$memberShip = MemberShip::create([
			'user_id'    => $user->id,
			'country_id' => Country::where("name", $request->country)->first()->id,
			'salutation' => $request->salutation,
			'phone'      => $request->phone,
			'street'     => $request->street,
			'postcode'   => $request->postcode,
			'city'       => $request->city,
			'iban'       => $request->iban,
		]);
		$memberShip->branches()->sync([Branch::AIR, ...$request->branches]);

		$user->sendEmailVerificationNotification();
		//Notification::send(User::notification()->get(), new NewAppointmentNotification());

		return response()->json([
			"message" => "Deine Anfrage wurde erfolgreich erstellt. Bitte bestätige deine Email!! Wir werden deine Anfrage schnellstmöglich bearbeiten.",
		], Response::HTTP_OK);
	}
}
