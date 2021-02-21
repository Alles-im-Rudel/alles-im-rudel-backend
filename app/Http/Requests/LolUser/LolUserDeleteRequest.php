<?php

namespace App\Http\Requests\LolUser;

use App\Traits\Requests\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LolUserDeleteRequest extends FormRequest
{
	use RequestHelper;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool
	{
		return Auth::user()->can('lol_users.delete');
	}

	public function prepareForValidation(): void
	{
		$this->convertToInteger('lolUserId');
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'lolUserId' => 'required|integer|exists:lol_users,id'
		];
	}
}
