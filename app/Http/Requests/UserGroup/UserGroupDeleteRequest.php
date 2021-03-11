<?php

namespace App\Http\Requests\UserGroup;

use App\Traits\Requests\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserGroupDeleteRequest extends FormRequest
{
	use RequestHelper;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool
	{
		return Auth::user()->can('user_groups.delete');
	}

	public function prepareForValidation(): void
	{
		$this->convertToInteger('userGroupId');
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'userGroupId' => 'required|integer|exists:user_groups,id'
		];
	}
}
