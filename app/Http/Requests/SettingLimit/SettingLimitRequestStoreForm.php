<?php

namespace App\Http\Requests\SettingLimit;

use Illuminate\Foundation\Http\FormRequest;

class SettingLimitRequestStoreForm extends FormRequest
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
            'limit_paylater' => 'required|numeric',
            'limit_credit' => 'required|numeric',
            'limit_loan' => 'required|numeric',
            'position' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'limit_paylater.required' => 'Limit Paylater is required',
            'limit_credit.required' => 'Limit Credit is required',
            'limit_loan.required' => 'Limit Loan is required',
            'position.required' => 'Position is required',
            'limit_paylater.numeric' => 'Limit Paylater must be a number',
            'limit_credit.numeric' => 'Limit Credit must be a number',
            'limit_loan.numeric' => 'Limit Loan must be a number',
            'position.string' => 'Position must be a string',
        ];
    }
}
