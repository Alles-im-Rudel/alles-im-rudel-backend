<?php

namespace App\Http\Controllers\Appointment;


use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Like;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AppointmentLikeController extends Controller
{

	/**
	 * @param  Appointment  $appointment
	 * @return JsonResponse
	 */
	public function checkLiked(Appointment $appointment): JsonResponse
	{
		return response()->json([
			'liked' => Auth::user()->hasLikedAppointment($appointment)
		]);
	}

	/**
	 * @param  Appointment  $appointment
	 * @return JsonResponse
	 */
	public function change(Appointment $appointment): JsonResponse
	{
		if (Auth::user()->hasLikedAppointment($appointment)) {
			Like::where('user_id', Auth::id())
				->where('likeable_id', $appointment->id)
				->where('likeable_type', get_class($appointment))->first()->delete();
			return response()->json([
				'liked' => false
			]);
		}
		$appointment->likes()->create([
			'user_id' => Auth::id()
		]);
		return response()->json([
			'liked' => true
		]);
	}

}
