<?php

namespace App\Http\Requests\Auth;

use App\Traits\Requests\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProfileUpdateRequest extends FormRequest
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
		$this->user = Auth::user();

		$this->convertToString('username');
		$this->convertToString('email');
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
			'email'          => 'required|email|unique:users,email,'.$this->user->id,
			'username'       => 'nullable|string|unique:users,username,'.$this->user->id,
			'firstName'      => 'required|string',
			'lastName'       => 'required|string',
			'password'       => 'nullable|string',
			'passwordRepeat' => 'nullable|string',
		];
	}
}
