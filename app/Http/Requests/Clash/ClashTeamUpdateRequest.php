<?php

namespace App\Http\Requests\Clash;

use App\Traits\Requests\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ClashTeamUpdateRequest extends FormRequest
{
	use RequestHelper;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool
	{
		return Auth::user()->can('clash_team.update');
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
			'name'              => 'required|max:255|unique:clash_teams,name,'. $this->clashTeam->id,
			'leaderId'          => 'required|integer|exists:users,id',
			'deletedMemberIds'  => 'nullable|array',
			'deletedMemberIds.' => 'nullable|integer|exists:clash_team_mebers,id',
			'newMembers'        => 'nullable|array'
		];
	}
}
