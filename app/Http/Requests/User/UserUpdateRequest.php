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
		return Auth::user()->can('users.update');
	}

	public function prepareForValidation(): void
	{
		$this->convertToInteger('userId');
		$this->convertToString('salutation');
		$this->convertToString('firstName');
		$this->convertToString('lastName');
		$this->convertToString('street');
		$this->convertToString('postcode');
		$this->convertToString('city');
		$this->convertToString('country');
		$this->convertToCarbonDate('birthday');
		$this->convertToString('email');
		$this->convertToString('phone');
		$this->convertToBoolean('wantsEmailNotification');
		$this->convertToBoolean('isActive');
		$this->convertToInteger('levelId');
		$this->convertToString('password');
		$this->convertToString('passwordRepeat');

		$this->convertToString('bankAccountbic');
		$this->convertToString('bankAccountIban');
		$this->convertToString('bankAccountFirstName');
		$this->convertToString('bankAccountLastName');
		$this->convertToString('bankAccountStreet');
		$this->convertToString('bankAccountPostcode');
		$this->convertToString('bankAccountCity');
		$this->convertToString('bankAccountCountry');


	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'userId'                 => 'required|integer|exists:users,id',
			'salutation'             => 'required|max:30|min:2',
			'firstName'              => 'required|max:30|min:2',
			'lastName'               => 'required|max:30|min:2',
			'street'                 => 'required|max:50|min:2',
			'postcode'               => 'required|max:6|min:5',
			'country'                => 'required|max:30|min:2',
			'birthday'               => 'required|date',
			'email'                  => 'required|email|max:50|min:3',
			'phone'                  => 'required|max:20|min:5',
			'wantsEmailNotification' => 'required|bool',
			'isActive'               => 'required|boolean',
			'levelId'                => 'required|integer|exists:levels,id',
			'password'               => 'nullable|string',
			'passwordRepeat'         => 'nullable|string',
			'bankAccountBic'         => 'required|max:30|min:8',
			'bankAccountIban'        => 'required|max:30|min:10',
			'bankAccountFirstName'   => 'required|max:30|min:2',
			'bankAccountLastName'    => 'required|max:30|min:2',
			'bankAccountStreet'      => 'required|max:50|min:2',
			'bankAccountPostcode'    => 'required|max:6|min:5',
			'bankAccountCountry'     => 'required|max:30|min:2',
		];
	}
}
