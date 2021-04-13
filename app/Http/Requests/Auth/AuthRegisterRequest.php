<?php

namespace App\Http\Requests\Auth;

use App\Traits\Requests\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;

class AuthRegisterRequest extends FormRequest
{
	use RequestHelper;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool
	{
		return true;
	}

	public function prepareForValidation(): void
	{
		$this->convertToString('email');
		$this->convertToString('username');
		$this->convertToCarbonDate('birthday');
		$this->convertToString('firstName');
		$this->convertToString('lastName');
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
			'email'          => 'required|unique:users|email',
			'password'       => 'required|string',
			'passwordRepeat' => 'required|string|same:password',
			'username'       => 'required|unique:users|string',
			'birthday'       => 'required|date',
			'firstName'      => 'required|string',
			'lastName'       => 'required|string',
		];
	}
}
