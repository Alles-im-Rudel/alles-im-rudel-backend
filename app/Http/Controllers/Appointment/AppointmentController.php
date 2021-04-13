<?php

namespace App\Http\Controllers\Appointment;


use App\Http\Controllers\Controller;
use App\Http\Requests\Appointment\AppointmentCreateRequest;
use App\Http\Requests\Appointment\AppointmentDeleteRequest;
use App\Http\Requests\Appointment\AppointmentIndexRequest;
use App\Http\Requests\Appointment\AppointmentUpdateRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
	/**
	 * @param  AppointmentIndexRequest  $request
	 * @return AnonymousResourceCollection
	 */
	public function index(AppointmentIndexRequest $request): AnonymousResourceCollection
	{
		$appointments = Appointment::with(['birthdayKid.thumbnail', 'tags'])
			->where('title', 'like', "%{$request->search}%")
			->where(static function ($query) use ($request) {
				$query->whereMonth('start_at', $request->month)
					->whereYear('start_at', $request->year);
			})
			->orWhere(static function ($query) use ($request) {
				$query->whereMonth('end_at', $request->month)
					->whereYear('end_at', $request->year);
			})
			->orWhere(static function ($query) use ($request) {
				$query->where('is_birthday', '=', true)
					->whereMonth('start_at', '=', $request->month);
			});
		if ($request->tagIds && count($request->tagIds) > 0) {
			$appointments = $appointments->whereHas('tags', static function ($query) use ($request) {
				$query->whereIn('tags.id', $request->tagIds);
			});
		}

		return AppointmentResource::collection($appointments->get());
	}

	/**
	 * @param  Appointment  $appointment
	 * @return AppointmentResource
	 */
	public function show(Appointment $appointment): AppointmentResource
	{
		$appointment->loadMissing('tags', 'user', 'birthdayKid')->loadCount('likes');
		return new AppointmentResource($appointment);
	}

	/**
	 * @param  AppointmentCreateRequest  $request
	 * @return JsonResponse
	 */
	public function store(AppointmentCreateRequest $request): JsonResponse
	{
		$appointment = Appointment::create([
			'title'      => $request->title,
			'text'       => $request->text,
			'color'      => $request->color,
			'is_all_day' => $request->isAllDay,
			'start_at'   => $request->startAt,
			'end_at'     => $request->endAt,
			'user_id'    => Auth::id()
		]);
		$appointment->tags()->sync($request->tagIds);
		return response()->json([
			'message' => 'Das Event wurde erfolgreich erstellt.'
		], Response::HTTP_CREATED);
	}

	/**
	 * @param  Appointment  $appointment
	 * @param  AppointmentUpdateRequest  $request
	 * @return JsonResponse
	 */
	public function update(Appointment $appointment, AppointmentUpdateRequest $request): JsonResponse
	{
		$appointment->tags()->sync($request->tagIds);
		$appointment->update([
			'title'      => $request->title,
			'text'       => $request->text,
			'color'      => $request->color,
			'is_all_day' => $request->isAllDay,
			'start_at'   => $request->startAt,
			'end_at'     => $request->endAt,
			'user_id'    => Auth::id()
		]);
		return response()->json([
			'message' => 'Das Event wurde erfolgreich bearbeitet.',
		], Response::HTTP_OK);
	}

	/**
	 * @param  Appointment  $appointment
	 * @param  AppointmentDeleteRequest  $request
	 * @return JsonResponse
	 * @throws Exception
	 */
	public function delete(Appointment $appointment, AppointmentDeleteRequest $request): JsonResponse
	{
		$appointment->tags()->detach();
		$appointment->delete();

		return response()->json([
			'message' => 'Das Event wurde erfolgreich gelöscht.',
		], Response::HTTP_OK);
	}
}
