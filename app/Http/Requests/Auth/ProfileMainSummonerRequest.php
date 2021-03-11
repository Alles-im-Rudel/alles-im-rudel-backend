<?php

namespace App\Http\Requests\Auth;

use App\Traits\Requests\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProfileMainSummonerRequest extends FormRequest
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
		$this->convertToInteger('mainSummonerId');
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'mainSummonerId' => 'nullable|integer|exists:summoners,id'
		];
	}
}
