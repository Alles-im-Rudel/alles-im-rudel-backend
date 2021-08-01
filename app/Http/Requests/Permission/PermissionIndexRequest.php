<?php

namespace App\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PermissionIndexRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool
    {
		return Auth::user()->can('permissions.index');
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
    {
		return [
			'withOutPermissionIds'   => 'nullable|array',
			'withOutPermissionIds.*' => 'nullable|integer|exists:permissions,id'
		];
	}
}
