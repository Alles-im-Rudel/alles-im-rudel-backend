<?php

namespace App\Http\Requests\Member;

use App\Traits\Requests\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class BranchMemberIndexRequest extends FormRequest
{
	use RequestHelper;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool
	{
		return Auth::user()->can('members.manage.new_branch');
	}

	public function prepareForValidation(): void
	{
		$this->convertToInteger('page');
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
			'page'   => 'required|integer',
			'search' => 'nullable|string',
		];
	}
}
