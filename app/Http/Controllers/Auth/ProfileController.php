<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ProfileIndexRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class ProfileController extends Controller
{
	/**
	 * @param  ProfileIndexRequest  $request
	 * @return UserResource
	 */
	public function index(ProfileIndexRequest $request): UserResource
	{
		$userId = Auth::id();

		return new UserResource(User::with(['summoners', 'userGroups', 'mainSummoner' ])->find($userId));
	}

	public function update()
	{

	}

	public function delete()
	{

	}
}
