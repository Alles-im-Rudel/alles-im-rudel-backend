<?php

namespace App\Http\Requests\User;

use App\Traits\Requests\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserPickerIndexRequest extends FormRequest
{
	use RequestHelper;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool
	{
		return Auth::user()->can('users.index');
	}

	public function prepareForValidation(): void
	{
		$this->convertToString('search');
		$this->convertToInteger('perPage');
		$this->convertToInteger('page');
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'search'  => 'nullable|string',
			'perPage' => 'required|integer|min:1',
			'page'    => 'required|integer|min:1',
			'userIds' => 'nullable|array'
		];
	}
}
