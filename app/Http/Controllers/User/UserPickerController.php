<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserPickerIndexRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserPickerController extends Controller
{
	/**
	 * @param  UserPickerIndexRequest  $request
	 * @return AnonymousResourceCollection
	 */
	public function index(UserPickerIndexRequest $request): AnonymousResourceCollection
	{
		$query = User::query();

		if ($request->search) {
			$query = $this->search("%{$request->search}%", $query);
		}
		if($request->userIds) {
			$query->whereNotIn('id', $request->userIds);
		}
		$query->orderBy('last_name');

		return UserResource::collection($query->paginate($request->perPage, '*', 'page', $request->page));
	}

	/**
	 * @param  string  $search
	 * @param  Builder  $query
	 * @return Builder
	 */
	protected function search(string $search, Builder $query): Builder
	{
		return $query->where('first_name', 'like', $search)
			->orWhere('last_name', 'like', $search)
			->orWhere('email', 'like', $search);
	}
}
