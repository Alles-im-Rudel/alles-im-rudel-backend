<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Images\ImageGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;


class UserImageController extends Controller
{
	/**
	 * @param  User  $user
	 * @param  Request  $request
	 * @return JsonResponse
	 * @throws ValidationException
	 */
	public function store(User $user, Request $request): JsonResponse
	{
		$this->validate($request, [
			'image' => 'required|mimes:jpg,jpeg,png,pdf|max:5120',
			'title' => 'nullable',
		]);
		$originalFileName = optional($request->file('image'))->getClientOriginalName();
		$image = ImageGenerator::resizeImageIfNeeded($request->file('image'));
		$thumbnail = ImageGenerator::createThumbnail($request->file('image'));
		$user->image()->delete();
		$user->image()->create([
			'image'          => $image->encode('data-url'),
			'thumbnail'      => $thumbnail->encode('data-url'),
			'file_name'      => $originalFileName,
			'title'          => $request->title,
			'file_mime_type' => $image->mime(),
			'file_size'      => $image->filesize()
		]);

		return response()->json([
			'message' => 'Upload success'
		]);
	}
}
