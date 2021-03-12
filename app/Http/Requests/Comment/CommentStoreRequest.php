<?php

namespace App\Http\Requests\Comment;

use App\Traits\Requests\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CommentStoreRequest extends FormRequest
{
	use RequestHelper;

	public function prepareForValidation(): void
	{
		$this->convertToString('modelType');
		$this->convertToInteger('modelId');
		$this->convertToString('comment');
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'comment'   => 'required|',
			'modelId'   => 'required|integer',
			'modelType' => 'required|commentable'
		];
	}
}
