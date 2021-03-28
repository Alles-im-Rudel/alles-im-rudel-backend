<?php

namespace App\Http\Controllers\Appointment;


use App\Http\Controllers\BaseController;
use App\Http\Requests\Appointment\AppointmentCreateRequest;
use App\Http\Requests\Appointment\AppointmentDeleteRequest;
use App\Http\Requests\Appointment\AppointmentIndexRequest;
use App\Http\Requests\Appointment\AppointmentUpdateRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends BaseController
{
	/**
	 * UserController constructor.
	 */
	public function __construct()
	{
		$this->builder = Appointment::query();
		$this->tableName = 'appointments';
		$this->searchFields = [
			'title'
		];
	}

	/**
	 * @param  AppointmentIndexRequest  $request
	 * @return AnonymousResourceCollection
	 */
	public function index(AppointmentIndexRequest $request): AnonymousResourceCollection
	{
		$this->search("%{$request->search}%");

		if ($request->tagIds && count($request->tagIds) > 0) {
			$this->builder = $this->getQuery()->whereHas('tags', static function ($query) use ($request) {
				$query->whereIn('tags.id', $request->tagIds);
			});
		}

		return AppointmentResource::collection($this->get());
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
