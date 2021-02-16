<?php

namespace App\Http\Requests\User;

use App\Traits\Requests\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserSyncUserGroupRequest extends FormRequest
{
	use RequestHelper;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool
	{
		return Auth::user()->can('user_groups.user.sync');
	}

	public function prepareForValidation(): void
	{
		$this->convertToInteger('userId');
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'userId'          => 'required|exists:users,id',
			'userGroupsIds'   => 'nullable|array',
			'userGroupsIds.*' => 'nullable|exists:user_groups,id'
		];
	}
}
