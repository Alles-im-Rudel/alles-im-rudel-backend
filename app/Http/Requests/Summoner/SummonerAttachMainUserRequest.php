<?php

namespace App\Http\Requests\Summoner;

use App\Traits\Requests\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SummonerAttachMainUserRequest extends FormRequest
{
	use RequestHelper;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool
	{
		return Auth::user()->can('summoners.main');
	}

	public function prepareForValidation(): void
	{
		$this->convertToInteger('mainSummonerId');
		$this->convertToInteger('userId');
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'mainSummonerId' => 'required|exists:summoners,id',
			'userId'         => 'required|exists:users,id'
		];
	}
}
