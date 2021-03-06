<?php

namespace App\Http\Requests\Clash;

use App\Traits\Requests\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ClashTeamDeleteRequest extends FormRequest
{
	use RequestHelper;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool
	{
		return Auth::user()->can('clash_team.delete');
	}

	public function prepareForValidation(): void
	{
		$this->convertToNumber('clashTeamId');
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'clashTeamId' => 'required|exists:clash_teams,id'
		];
	}
}
