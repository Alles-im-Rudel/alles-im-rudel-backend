<?php

namespace App\Http\Requests\LolUser;

use App\Traits\Requests\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LolUserUpdateRequest extends FormRequest
{
	use RequestHelper;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool
	{
		return Auth::user()->can('lol_users.update');
	}

	public function prepareForValidation(): void
	{
		$this->convertToInteger('lolUserId');
		$this->convertToString('name');
		$this->convertToBoolean('isMain');
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'lolUserId' => 'required|integer|exists:lol_users,id',
			'name'      => 'nullable|max:60|min:1',
			'isMain'    => 'required|boolean',
		];
	}
}
