<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequestStoreForm extends FormRequest
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
            'name' => 'required',
            'email' => 'required|email|unique:employees,email',
            'password' => 'required|min:8',
            'role' => 'required',
            'position' => 'required',
            'department' => 'required',
            'nik' => 'required|unique:employees,nik',
        ];
    }

    public function messages(): array
    {
        return [
          'name.required' => 'Name is required',
          'email.required' => 'Email is required',
          'password.required' => 'Password is required',
          'role.required' => 'Role is required',
          'position.required' => 'Position is required',
          'department.required' => 'Department is required',
          'email.unique' => 'Email already exists',
            'nik.required' => 'Nik is required',
            'nik.unique' => 'Nik already exists',
        ];
    }
}
