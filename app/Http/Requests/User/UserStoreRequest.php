<?php

namespace App\Http\Requests\User;

use App\Traits\Requests\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserStoreRequest extends FormRequest
{
    use RequestHelper;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::user()->can('users.store');
    }

    public function prepareForValidation(): void
    {
        $this->convertToInteger('levelId');
        $this->convertToString('firstName');
        $this->convertToString('lastName');
        $this->convertToString('email');
        $this->convertToCarbonDate('birthday');
        $this->convertToBoolean('wantsEmailNotification');
        $this->convertToBoolean('isActive');
        $this->convertToString('password');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'levelId'                => 'required|integer|exists:levels,id',
            'firstName'              => 'nullable|max:30|min:2',
            'lastName'               => 'nullable|max:30|min:2',
            'email'                  => 'required|email|max:50|min:3',
            'wantsEmailNotification' => 'required|bool',
            'birthday'               => 'nullable|date',
            'isActive'               => 'required|boolean',
            'password'               => 'nullable|string'
        ];
    }
}
