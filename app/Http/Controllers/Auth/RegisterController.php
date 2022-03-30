<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Level;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
	/**
	 * @param  \App\Http\Requests\Auth\RegisterRequest  $request
	 * @return JsonResponse
	 */
	public function register(RegisterRequest $request): JsonResponse
	{
		$user = User::create([
			'first_name'        => $request->firstName,
			'last_name'         => $request->lastName,
			'username'          => $request->username,
			'email'             => $request->email,
			'birthday'          => $request->birthday,
			'activated_at'      => $request->activatedAt,
			'level_id'          => Level::GUEST,
			'email_verified_at' => null,
			'password'          => Hash::make($request->password),
		]);

		$user->userGroups()->sync(UserGroup::GUEST_ID);

		$user->memberShip()->create([
			'country_id'   => $request->countryId,
			'phone'        => $request->phone,
			'street'       => $request->street,
			'postcode'     => $request->postcode,
			'city'         => $request->city,
			'iban'         => $request->iban,
			'activated_at' => false,
		]);

		$user->memberShip()->branch()->sync($request->branchIds);

		$user->sendEmailVerificationNotification();

		return response()->json();
	}
}
