<?php

namespace App\Http\Controllers\Tag;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResouce;
use App\Models\Tag;
use App\Models\User;
use App\Services\Images\ImageGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\ValidationException;


class TagController extends Controller
{
	/**
	 * @return AnonymousResourceCollection
	 */
	public function all(): AnonymousResourceCollection
	{
		return TagResouce::collection(Tag::all());
	}
}
