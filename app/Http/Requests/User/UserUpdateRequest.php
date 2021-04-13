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
		$this->convertToInteger('levelId');
		$this->convertToString('firstName');
		$this->convertToString('lastName');
		$this->convertToString('username');
		$this->convertToString('email');
		$this->convertToCarbonDate('birthday');
		$this->convertToBoolean('isActive');
		$this->convertToString('password');
		$this->convertToString('passwordRepeat');
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'userId'         => 'required|integer|exists:users,id',
			'levelId'        => 'required|integer|exists:levels,id',
			'firstName'      => 'nullable|max:30|min:2',
			'lastName'       => 'nullable|max:30|min:2',
			'username'       => 'required|max:20|min:1',
			'email'          => 'required|email|max:50|min:3',
			'birthday'       => 'nullable|date',
			'isActive'       => 'required|boolean',
			'password'       => 'nullable|string',
			'passwordRepeat' => 'nullable|string',
		];
	}
}
