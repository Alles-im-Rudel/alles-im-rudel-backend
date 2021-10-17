<?php

namespace App\Http\Requests\Auth;

use App\Traits\Requests\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
		$this->convertToInteger('countryId');
		$this->convertToString('phone');
		$this->convertToString('street');
		$this->convertToString('postcode');
		$this->convertToString('city');
		$this->convertToString('iban');
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
			'email'          => 'required|unique:users|email|max:255',
			'password'       => 'required|string|max:255',
			'passwordRepeat' => 'required|string|same:password|max:255',
			'username'       => 'required|unique:users|string|max:255',
			'birthday'       => 'required|date',
			'firstName'      => 'required|string|max:255',
			'lastName'       => 'required|string|max:255',
			'countryId'      => 'required|integer|exists:countries,id',
			'phone'          => 'required|string|max:255',
			'street'         => 'required|string|max:255',
			'postcode'       => 'required|string|max:6',
			'city'           => 'required|string|max:255',
			'iban'           => 'required|string|max:255',
			'brancheIds'     => 'required|array',
			'brancheIds.*'   => 'required|exists:branches,id'
		];
	}
}
