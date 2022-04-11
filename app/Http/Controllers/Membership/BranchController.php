<?php

namespace App\Http\Controllers\MemberShip;

use App\Http\Controllers\Controller;
use App\Http\Resources\BranchResource;
use App\Models\Branch;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BranchController extends Controller
{
	/**
	 * @return AnonymousResourceCollection
	 */
	public function index(): AnonymousResourceCollection
	{
		return BranchResource::collection(Branch::withCount('branchUserMemberShips')->get());
	}
}
