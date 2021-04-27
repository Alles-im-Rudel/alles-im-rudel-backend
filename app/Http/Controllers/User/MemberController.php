<?php

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Http\Requests\User\MemberIndexRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MemberController extends Controller
{
	/**
	 * @param  MemberIndexRequest  $request
	 * @return AnonymousResourceCollection
	 */
	public function index(MemberIndexRequest $request): AnonymousResourceCollection
	{
		$users = User::member()
			->with('thumbnail', 'userGroups')
			->orderByDesc('level_id');
		if ($request->search) {
			$users->where(static function ($query) use ($request) {
				$query->where('username', 'like', "%{$request->search}%")
					->orWhere('first_name', 'like', "%{$request->search}%")
					->orWhere('last_name', 'like', "%{$request->search}%")
					->orWhere('email', 'like', "%{$request->search}%");
			});
		}

		return UserResource::collection($users->paginate(9, '*', $request->page, $request->page));
	}
}
