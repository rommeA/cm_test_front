<?php

namespace App\Http\Requests\Site\Contractor;

use App\Models\Contractor;
use App\Models\Country;
use Illuminate\Foundation\Http\FormRequest;

class StoreContractorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('create', Contractor::class);
    }

    protected function formatPhone($phone): array|string
    {
        $new_phone = str_replace('(', '', $phone);
        $new_phone = str_replace(')', '', $new_phone);

        return str_replace('+', '', $new_phone);
    }

    protected function prepareForValidation()
    {
        if ($this->has('phone') && !empty($this->phone) &&
            !(strlen($this->phone) === 1 && $this->phone[0] === '+')
        ) {
            $this->merge(['phone' => $this->formatPhone($this->phone)]);
        }
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'nullable|string',
            'name_ru' => 'nullable|string',
            'short_name' => 'nullable|string',
            'short_name_ru' => 'nullable|string',
            'type' => 'nullable|string',
            'country_id' => 'nullable|exists:countries,id',
            'official_address' => 'nullable|string',
            'current_address' => 'nullable|string',
            'fax' => 'nullable|string',
            'phone' => ['nullable', 'regex:/^\+*[0-9]+$/'],
            'email' => ['nullable', 'email:rfc,dns'],
            'UTR' => 'nullable|string',
            'KPP' => 'nullable|string',
            'OGRN' => 'nullable|string',
            'director_id' => 'nullable|exists:users,id',
            'responsible_id' => 'nullable|exists:users,id',
            'commerce_fields.*' => 'nullable|exists:commerce_fields,id',
            'bank_name' => 'nullable|string',
            'RCBIC' => 'nullable|string',
            'account' => 'nullable|string',
            'correspondent_account' => 'nullable|string',
            'SWIFT' => 'nullable|string',
            'currency_id' => 'nullable|exists:currencies,id',
        ];

        if ($this->has('account') && !empty($this->account)) {
            $rules = array_merge($rules, [
                'bank_name' => 'required',
                'RCBIC' => 'required',
                'correspondent_account' => 'required',
                'SWIFT' => 'required',
                'currency_id' => 'required',
            ]);
        }

        $russia = Country::where('iso_name', 'RU')->first();

        if ($this->has('country_id') && $this->country_id === $russia->id) {
            $rules = array_merge($rules, [
                'name_ru' => 'required',
                'short_name_ru' => 'required',
            ]);
        } else {
            $rules = array_merge($rules, [
                'name' => 'required',
                'short_name' => 'required',
            ]);
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'English name is required.',
            'short_name.required' => 'English short name is required.',
            'name_ru.required' => 'Russian name is required.',
            'short_name_ru.required' => 'Russian short name is required.',
        ];
    }
}
