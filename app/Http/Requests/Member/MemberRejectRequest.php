<?php

namespace App\Http\Requests\Member;

use App\Traits\Requests\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;

class MemberRejectRequest extends FormRequest
{
	use RequestHelper;

	public function prepareForValidation(): void
	{
		$this->convertToString('salutation');
		$this->convertToString('firstName');
		$this->convertToString('lastName');
		$this->convertToString('phone');
		$this->convertToCarbonDate('birthday');
		$this->convertToString('street');
		$this->convertToString('postcode');
		$this->convertToString('city');
		$this->convertToString('country');
		$this->convertToString('iban');
		$this->convertToString('email');
		$this->convertToString('password');
		$this->convertToString('passwordRepeat');
		$this->convertToBoolean('hasAcceptedDataProtection');
		$this->convertToBoolean('hasAcceptedMonthlyDebits');
		$this->convertToBoolean('wantsEmailNotification');
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'salutation'                => 'required|string',
			'firstName'                 => 'required|string',
			'lastName'                  => 'required|string',
			'phone'                     => 'required|string',
			'birthday'                  => 'required|date',
			'street'                    => 'required|string',
			'postcode'                  => 'required|string',
			'city'                      => 'required|string',
			'country'                   => 'required|string',
			'iban'                      => 'required|string',
			'email'                     => 'required|email|max:50|min:3|unique:users',
			'password'                  => 'string|required_with:passwordRepeat|same:passwordRepeat',
			'passwordRepeat'            => 'required|string',
			'hasAcceptedDataProtection' => 'required|boolean',
			'hasAcceptedMonthlyDebits'  => 'required|boolean',
			'wantsEmailNotification'    => 'required|boolean',
		];
	}
}
