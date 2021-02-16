<?php

namespace App\Http\Controllers\UserGroup;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserGroup\UserGroupIndexRequest;
use App\Http\Resources\UserGroupResource;
use App\Models\UserGroup;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserGroupController extends Controller
{
	/**
	 * @param  UserGroupIndexRequest  $request
	 * @return AnonymousResourceCollection
	 */
	public function index(UserGroupIndexRequest $request): AnonymousResourceCollection
	{
		$userGroups = UserGroup::query();
		if ($request->withOutUserGroupIds && count($request->withOutUserGroupIds) > 0) {
			$userGroups->whereNotIn('id', $request->withOutUserGroupIds);
		}
		return UserGroupResource::collection($userGroups->get());
	}
}
