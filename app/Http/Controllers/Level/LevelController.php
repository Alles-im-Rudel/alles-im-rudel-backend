<?php

namespace App\Http\Controllers\Level;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Level\LevelIndexRequest;
use App\Http\Resources\LevelResource;
use App\Models\Level;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LevelController extends BaseController
{
	/**
	 * @param  LevelIndexRequest  $request
	 * @return AnonymousResourceCollection
	 */
	public function index(LevelIndexRequest $request): AnonymousResourceCollection
	{
		return LevelResource::collection(Level::all());
	}

}
