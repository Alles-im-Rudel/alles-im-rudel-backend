<?php

namespace App\Http\Requests\User;

use App\Traits\Requests\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserDownloadIndexRequest extends FormRequest
{
	use RequestHelper;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool
	{
		return Auth::user()->can('users.download') || Auth::user()->can('members.e_sports') || Auth::user()->can('members.airsoft') || Auth::user()->can('members.allesimrudel');
	}

	public function prepareForValidation(): void
	{
		$this->convertToInteger('branchId');
		$this->convertToString('search');
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'branchId' => 'nullable|integer|exists:branches,id',
			'search'   => 'nullable|string',
		];
	}
}
