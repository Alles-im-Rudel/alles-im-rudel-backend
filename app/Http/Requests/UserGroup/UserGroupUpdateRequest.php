<?php

namespace App\Http\Requests\UserGroup;

use App\Traits\Requests\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserGroupUpdateRequest extends FormRequest
{
	use RequestHelper;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool
	{
		return Auth::check();
	}

	public function prepareForValidation(): void
	{
		$this->convertToInteger('userGroupId');
		$this->convertToInteger('levelId');
		$this->convertToString('displayName');
		$this->convertToString('description');
		$this->convertToString('color');
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'userGroupId' => 'required|integer|exists:user_groups,id',
			'levelId'     => 'required|integer|exists:levels,id',
			'displayName' => 'required|max:30|min:2',
			'color'       => 'required|max:15|min:2',
			'description' => 'nullable|max:500|min:2',
		];
	}
}
