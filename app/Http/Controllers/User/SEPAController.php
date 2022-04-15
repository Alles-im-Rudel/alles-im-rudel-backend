<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Member\SEPAIndexRequest;
use App\Http\Resources\UserResource;
use App\Models\BranchUserMemberShip;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class SEPAController extends Controller
{
	/**
	 * @param  \App\Http\Requests\Member\SEPAIndexRequest  $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function index(SEPAIndexRequest $request): JsonResponse
	{
		if (!Auth::user()->can('members.mamage')) {
			return response()->json(["msg" => "Keine Berechtigung"], 403);
		}

		$sepaChanges = User::with([
			'bankAccount',
			'branchUserMemberShips' => function ($query) {
				$query->withTrashed()->whereNull('exported_at')->whereNotNull('activated_at');
			},
			'branchUserMemberShips.branch'
		])->whereHas('branchUserMemberShips', function ($query) {
			$query->withTrashed()->whereNull('exported_at')->whereNotNull('activated_at');
		})->get()->map(function ($item) {
			return [
				'user'        => new UserResource($item),
				'mandate'     => 'AIR '.$item->id,
				'mandateDate' => $item->bankAccount->created_at,
				'value'       => $this->getValue($item->id),
			];
		});

		return response()->json([
			"sepaChanges" => $sepaChanges,
		], Response::HTTP_OK);
	}

	/**
	 * @param  \App\Models\BranchUserMemberShip  $branchUserMemberShip
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function exported(BranchUserMemberShip $branchUserMemberShip): JsonResponse
	{
		if (!Auth::user()->can('members.mamage')) {
			return response()->json(["msg" => "Keine Berechtigung"], 403);
		}


		$branchUserMemberShip->exported_at = now();

		if ($branchUserMemberShip->wants_to_leave) {
			$branchUserMemberShip->deleted_at = now();
		}

		$branchUserMemberShip->save();

		return response()->json([
			"message" => "Eintrage bearbeitet",
		], Response::HTTP_OK);
	}

	protected function getValue($userId): int
	{
		$value = 0;
		$branchUserMemberShips = BranchUserMemberShip::query()
			->where('user_id', $userId)
			->whereNotNull('activated_at')
			->whereNull('wants_to_leave_at')
			->get();
		foreach ($branchUserMemberShips as $branchUserMemberShip) {
			$value += $branchUserMemberShip->branch->price;
		}
		return $value;
	}
}
