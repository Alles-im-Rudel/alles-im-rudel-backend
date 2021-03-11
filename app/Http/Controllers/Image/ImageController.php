<?php

namespace App\Http\Controllers\Image;

use App\Http\Controllers\BaseController;
use App\Models\Image;
use Exception;
use Illuminate\Http\JsonResponse;

class ImageController extends BaseController
{
	/**
	 * @param  Image  $image
	 * @return JsonResponse
	 * @throws Exception
	 */
	public function delete(Image $image): JsonResponse
	{
		$image->delete();
		return response()->json([
			'message' => 'Das Bild wurde gelöscht'
		]);
	}

}
