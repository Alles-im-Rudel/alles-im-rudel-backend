<?php

namespace App\Http\Requests\UserGroup;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserGroupAllReqeust extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool
	{
		return Auth::user()->can('user_groups.index');
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'withOutUserGroupIds'   => 'nullable|array',
			'withOutUserGroupIds.*' => 'nullable|integer|exists:user_groups,id'
		];
	}
}
