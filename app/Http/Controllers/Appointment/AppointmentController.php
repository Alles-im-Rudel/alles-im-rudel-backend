<?php

namespace App\Http\Controllers\Appointment;


use App\Http\Controllers\Controller;
use App\Http\Requests\Appointment\AppointmentCreateRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{

	/**
	 * @param  Request  $request
	 * @return AnonymousResourceCollection
	 */
	public function index(Request $request): AnonymousResourceCollection
	{
		$appountments = Appointment::with('tags', 'user', 'birthdayKid')->get();

		return AppointmentResource::collection($appountments);
	}

	/**
	 * @param  AppointmentCreateRequest  $request
	 * @return JsonResponse
	 */
	public function store(AppointmentCreateRequest $request): JsonResponse
	{
		Appointment::create([
			'title'      => $request->title,
			'text'       => $request->text,
			'color'      => $request->color,
			'is_all_day' => $request->isAllDay,
			'start_at'   => $request->startAt,
			'end_at'     => $request->endAt,
			'user_id'    => Auth::id()
		]);
		return response()->json([
			'message' => 'Das Event wurde Erfolgreich erstellt'
		], Response::HTTP_CREATED);
	}
}
