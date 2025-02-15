<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminUserRequest extends FormRequest
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
        $id = $this->route('user') ?? null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($id, 'id')],
            'mobile' => ['required', 'digits:10', 'regex:/[6-9]\d{9}$/', Rule::unique('user_details', 'mobile')->ignore($id, 'user_id')],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:200'],
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
            'avatar.image' => 'Not an image',
            'avatar.mimes' => 'File type not allowed (only jpeg, png, jpg, gif and svg)',
            'avatar.max' => 'File size must be less than :max KB',
        ];
    }
}
