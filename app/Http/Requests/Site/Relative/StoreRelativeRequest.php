<?php

namespace App\Http\Requests\Site\Relative;

use Illuminate\Foundation\Http\FormRequest;

class StoreRelativeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->get('consent_personal_data')) {
            $this->merge(['consent_personal_data' => (bool)$this->get('consent_personal_data')]);
        }

        if ($this->get('is_beneficiary')) {
            $this->merge(['is_beneficiary' => (bool)$this->get('is_beneficiary')]);
        }
    }

    public function rules(): array
    {
        $rules =  [
            'full_name' => ['required', 'regex:/^[A-zА-яёЁ]+((\s)?((\'|\-|\.)?([A-zА-яёЁ])+))*$/u'],
            'relative_type_id' => 'required|exists:relative_types,id',
            'home_phone' => 'required_without:mobile_phone',
            'mobile_phone' => 'required_without:home_phone',
            'passport_series' => 'required_with:is_beneficiary',
            'passport_number' => 'required_with:is_beneficiary',
            'passport_place' => 'required_with:is_beneficiary',
            'passport_date_issue' => 'required_with:is_beneficiary',
            'consent_personal_data' => 'required|boolean',
            "user_id" => "required|exists:users,id",
            "date_birth" => 'nullable|date',
            "address" => 'nullable|string',
            "zip_code" => 'nullable|string',
            "country" => 'nullable|string',
            "region" => 'nullable|string',
            "city" => 'nullable|string',
            "street" => 'nullable|string',
            "building" => 'nullable|string',
            "apartment" => 'nullable|string',
            "is_beneficiary" => 'nullable|boolean',
        ];

        if ($this->get('is_beneficiary')) {
            $rules['email'] = 'required|email:rfc,dns';
        }

        return $rules;
    }
}
