<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permission\PermissionIndexRequest;
use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PermissionController extends Controller
{
	/**
	 * @param  PermissionIndexRequest  $request
	 * @return AnonymousResourceCollection
	 */
	public function index(PermissionIndexRequest $request): AnonymousResourceCollection
	{
		$permissions = Permission::query();
		if ($request->withOutPermissionIds && count($request->withOutPermissionIds) > 0) {
			$permissions->whereNotIn('id', $request->withOutPermissionIds);
		}
		return PermissionResource::collection($permissions->get());
	}
}
