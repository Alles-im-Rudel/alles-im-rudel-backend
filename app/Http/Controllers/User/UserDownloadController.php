<?php

namespace App\Http\Controllers\User;

use App\Exports\UsersExport\UsersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserDownloadIndexRequest;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UserDownloadController extends Controller
{


	/**
	 * @param  \App\Http\Requests\User\UserDownloadIndexRequest  $request
	 * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
	 */
	public function index(UserDownloadIndexRequest $request): BinaryFileResponse
	{
		return Excel::download(new UsersExport($request->search, $request->branchId), 'users.xlsx');
	}
}
