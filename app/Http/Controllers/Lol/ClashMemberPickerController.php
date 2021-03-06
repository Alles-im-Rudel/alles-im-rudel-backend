<?php

namespace App\Http\Controllers\Lol;

use App\Http\Controllers\Controller;
use App\Http\Requests\Clash\ClashMemberPickerIndexRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ClashMemberPickerController extends Controller
{
	/**
	 * @param  ClashMemberPickerIndexRequest  $request
	 * @return AnonymousResourceCollection
	 */
	public function index(ClashMemberPickerIndexRequest $request): AnonymousResourceCollection
	{
		$query = User::query()->has('mainSummoner');

		if ($request->search) {
			$query = $this->search("%{$request->search}%", $query);
		}
		if($request->clashMemberIds) {
			$query->whereNotIn('id', $request->clashMemberIds);
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
			->orWhere('username', 'like', $search)
			->orWhere('email', 'like', $search);
	}
}
