<?php

namespace App\Http\Requests\Summoner;

use App\Traits\Requests\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SummonerInfoIndexRequest extends FormRequest
{
	use RequestHelper;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool
	{
		return Auth::user()->can('summoners.show');
	}

	public function prepareForValidation(): void
	{
		$this->convertToInteger('beginIndex');
		$this->convertToInteger('endIndex');
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'beginIndex' => 'required|integer',
			'endIndex'   => 'required|integer'
		];
	}
}
