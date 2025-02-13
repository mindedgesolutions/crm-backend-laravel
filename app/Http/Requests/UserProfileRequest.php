<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore(Auth::id(), 'id')],
            'mobile' => ['required', 'digits:10', 'regex:/[6-9]\d{9}$/', Rule::unique('user_details', 'mobile')->ignore(Auth::id(), 'user_id')],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:512'],
        ];
    }

    public function attributes()
    {
        return [
            'mobile' => 'mobile no.',
            'avatar' => 'profile picture',
        ];
    }

    public function messages()
    {
        return [
            '*.unique' => ':Attribute already exists',
            '*.required' => ':Attribute is required',
            'email.email' => 'Invalid email',
            'mobile.regex' => 'Invalid mobile no.',
            'mobile.digits' => 'Mobile no. must be :digits digits',
        ];
    }
}
