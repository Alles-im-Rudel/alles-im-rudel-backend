<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Member\SEPAIndexRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class SEPAController extends Controller
{
	/**
	 * @param  \App\Http\Requests\Member\SEPAIndexRequest  $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function index(SEPAIndexRequest $request): JsonResponse
	{
		$newMembers = User::with([
			'memberShip', 'memberShip.branchesWithTrashed' => function ($query) {
				return $query->whereNull('branch_member_ship.exported_at');
			}
		])->whereHas('memberShip', function ($query) {
			return $query->whereNotNull('activated_at')
				->whereHas('branchesWithTrashed', function ($query) {
					return $query->whereNull('branch_member_ship.exported_at');
				});
		})->get()->map(function ($item) {
			return [
				'name'        => $item->first_name.' '.$item->last_name,
				'iban'        => $item->memberShip->iban,
				'mandate'     => 'AIR '.$item->memberShip->id,
				'mandateDate' => $item->memberShip->created_at,
				'value'       => $this->getValue($item->memberShip->branchesWithTrashed),
				'sepaDate'    => $this->getSepaDate($item->memberShip),
				'pivotId'     => $item->memberShip->branches,
				'state'       => $this->getState($item->memberShip),
			];
		});

		return response()->json([
			"members" => $newMembers,
		], Response::HTTP_OK);
	}

	protected function getValue($branches): int
	{
		$value = 0;
		foreach ($branches as $branch) {
			if ($branch->pivot->deleted_at === null) {
				$value += $branch->price;
			}
		}
		return $value;
	}

	protected function getSepaDate($memberShip): Carbon
	{
		$date = Carbon::parse($memberShip->activated_at)->addMonth()->startOfMonth();

		foreach ($memberShip->branchesWithTrashed as $branch) {
			$newDate = Carbon::parse($branch->pivot->updated_at)->addMonth()->startOfMonth();
			if ($date < $newDate) {
				$date = $newDate;
			}
		}
		return $date;
	}

	protected function getState($memberShip)
	{
		$test = false;
		foreach ($memberShip->branches as $branch) {
			if ($branch->pivot->exported_at) {
				$test = true;
			}
			if ($branch->pivot->deleted_at) {
				$test = true;
			}
		}
		return $test;
	}
}
