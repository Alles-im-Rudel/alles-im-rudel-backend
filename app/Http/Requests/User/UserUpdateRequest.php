<?php

namespace App\Http\Requests\User;

use App\Traits\Requests\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserUpdateRequest extends FormRequest
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
		$this->convertToInteger('userId');
		$this->convertToString('firstName');
		$this->convertToString('lastName');
		$this->convertToString('username');
		$this->convertToString('email');
		$this->convertToBoolean('isActive');
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'userId'    => 'required|integer|exists:users,id',
			'firstName' => 'nullable|max:30|min:2',
			'lastName'  => 'nullable|max:30|min:2',
			'username'  => 'requierd|max:20|min:1',
			'email'     => 'requierd|email|max:50|min:3',
			'isActive'  => 'requierd|boolean',
		];
	}
}
