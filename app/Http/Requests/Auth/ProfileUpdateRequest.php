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
		$this->convertToString('email');
		$this->convertToCarbonDate('birthday');
		$this->convertToString('firstName');
		$this->convertToBoolean('wantsEmailNotification');
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
	    $id = Auth::id();
		return [
			'email'                  => 'required|email|unique:users,email,'.$id,
			'birthday'               => 'nullable|date',
			'firstName'              => 'required|string',
			'wantsEmailNotification' => 'required|bool',
			'lastName'               => 'required|string',
			'password'               => 'nullable|string',
			'passwordRepeat'         => 'nullable|string',
		];
	}
}
