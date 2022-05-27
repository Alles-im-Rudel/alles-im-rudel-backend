<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use App\Http\Resources\BranchResource;
use App\Models\Branch;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
	/**
	 * @return AnonymousResourceCollection
	 */
	public function index(): AnonymousResourceCollection
	{
		return BranchResource::collection(Branch::withCount('branchUserMemberShips')->get());
	}

	/**
	 * @return AnonymousResourceCollection
	 */
	public function filterd(): AnonymousResourceCollection
	{
		$branches = Branch::withCount('branchUserMemberShips');
		$branchIds = Auth::user()->getAvailableBranchIds();

		return BranchResource::collection($branches->whereIn('id', $branchIds)->get());

	}
}
