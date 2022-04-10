<?php

namespace App\Http\Controllers\MemberShip;

use App\Http\Controllers\Controller;
use App\Http\Resources\BranchResource;
use App\Models\Branch;
use App\Models\MemberShip;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class MandatController extends Controller
{
	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function index(): JsonResponse
	{
		$max = MemberShip::max('id');
		if ($max) {
			$id = 'AIR '.($max + 1);
			return response()->json([
				"data" => $id,
			], Response::HTTP_OK);
		}
		return response()->json([
			"data" => 'AIR 1',
		], Response::HTTP_OK);
	}
}
