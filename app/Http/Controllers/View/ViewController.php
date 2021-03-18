<?php

namespace App\Http\Controllers\View;


use App\Http\Controllers\Controller;
use App\Http\Requests\View\ViewUpdateRequest;
use App\Http\Resources\ViewResource;
use App\Models\View;
use Illuminate\Http\JsonResponse;


class ViewController extends Controller
{

	/**
	 * @param  View  $view
	 * @return ViewResource
	 */
	public function show(View $view): ViewResource
	{
		return new ViewResource($view);
	}

	/**
	 * @param  View  $view
	 * @param  ViewUpdateRequest  $request
	 * @return JsonResponse
	 */
	public function update(View $view, ViewUpdateRequest $request): JsonResponse
	{
		$view->update([
			'title' => $request->title,
			'body'  => $request->body
		]);

		return response()->json([
			'message' => 'Die Seite wurde erfolgreich bearbeitet',
			'view'    => new ViewResource($view),
		]);
	}
}
