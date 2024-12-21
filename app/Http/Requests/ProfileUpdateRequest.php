<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('User', 'username')->ignore($this->user()->user_id, 'user_id'),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:100',
                Rule::unique('User', 'email')->ignore($this->user()->user_id, 'user_id'),
            ],
            'fullname' => ['required', 'string', 'max:100'],
            'nif' => ['required', 'string', 'max:20'],
        ];
    }


}
