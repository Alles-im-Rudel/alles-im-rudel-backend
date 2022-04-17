<?php

namespace App\Http\Controllers\MemberShipApplication;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class MandatController extends Controller
{
	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function index(): JsonResponse
	{
		$max = User::max('id');
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
