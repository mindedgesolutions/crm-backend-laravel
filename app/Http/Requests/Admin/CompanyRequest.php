<?php

namespace App\Http\Requests\Admin;

use App\Models\UserDetail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;

class CompanyRequest extends FormRequest
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
        $encId = $this->route('company');
        $id = Crypt::decrypt($encId);
        $userId = UserDetail::where('company_id', $id)->first()->user_id;

        return [
            'name' => 'required|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('companies', 'email')->ignore($id)],
            'website' => 'nullable|url|max:255',
            'address' => 'required|max:255',
            'location' => 'required|max:255',
            'pincode' => 'required|digits:6',
            'contactPerson' => 'required|string|max:255',
            'mobile' => ['required', 'digits:10', 'regex:/[6-9]\d{9}$/', Rule::unique('companies', 'phone')->ignore($id)],
            'whatsapp' => ['required', 'digits:10', 'regex:/[6-9]\d{9}$/', Rule::unique('companies', 'whatsapp')->ignore($id)],
            'username' => ['required', 'string', 'max:255'],
            'userEmail' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
        ];
    }

    public function attributes()
    {
        return [
            'pincode' => 'PIN code',
            'contactPerson' => 'contact person',
            'userEmail' => 'user email',
            'username' => 'app user name',
            'whatsapp' => 'WhatsApp no.',
        ];
    }

    public function messages()
    {
        return [
            '*.unique' => ':Attribute already exists',
            '*.required' => ':Attribute is required',
            '*.max' => ':Attribute should not be more than :max characters',
            '*.email' => 'Invalid email',
            '*.regex' => 'Invalid :attribute',
            '*.digits' => ':Attribute should be :digits digits',
        ];
    }
}
