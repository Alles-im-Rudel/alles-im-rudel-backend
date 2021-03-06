<?php

namespace App\Http\Requests\Clash;

use App\Traits\Requests\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ClashTeamCreateRequest extends FormRequest
{
	use RequestHelper;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool
	{
		return Auth::user()->can('clash_team.create');
	}

	public function prepareForValidation(): void
	{
		$this->convertToString('name');
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'name' => 'required|unique:clash_teams|max:255'
		];
	}
}
