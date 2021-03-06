<?php

namespace App\Http\Requests\Summoner;

use App\Traits\Requests\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SummonerPickerIndexRequest extends FormRequest
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
		$this->convertToBoolean('freeMain');
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'search'   => 'required|string',
			'freeMain' => 'nullable|boolean'
		];
	}
}
