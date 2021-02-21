<?php

namespace App\Http\Requests\LolUser;

use App\Traits\Requests\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LolUserIndexRequest extends FormRequest
{
	use RequestHelper;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool
	{
		return Auth::user()->can('lol_users.index');
	}

	public function prepareForValidation(): void
	{
		$this->convertToInteger('perPage');
		$this->convertToInteger('page');
		$this->convertToString('search');
		$this->convertToString('sortBy');
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'perPage' => 'required|integer',
			'page'    => 'required|integer|min:1',
			'search'  => 'nullable|string',
			'sortBy'  => 'string|nullable',
		];
	}
}
