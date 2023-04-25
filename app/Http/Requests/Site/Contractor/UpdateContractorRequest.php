<?php

namespace App\Http\Requests\Site\Contractor;

use App\Models\Contractor;
use App\Models\Country;
use Illuminate\Foundation\Http\FormRequest;

class UpdateContractorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('update', Contractor::class);
    }

    protected function formatPhone($phone): array|string
    {
        $new_phone = str_replace('(', '', $phone);
        $new_phone = str_replace(')', '', $new_phone);

        return str_replace('+', '', $new_phone);
    }

    protected function prepareForValidation()
    {
        if (
            $this->has('phone')
            && !empty($this->phone)
            && !(strlen($this->phone) === 1 && $this->phone[0] === '+')
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
            'country_id' => 'nullable|exists:countries,id',
            'official_address' => 'nullable|string',
            'current_address' => 'nullable|string',
            'phone' => 'nullable|regex:/^\+*[0-9]+$/',
            'email' => 'nullable|email:rfc,dns',
            'fax' => 'nullable|string',
            'UTR' => 'nullable|string',
            'KPP' => 'nullable|string',
            'OGRN' => 'nullable|string',
            'commerce_fields.*' => 'nullable|exists:commerce_fields,id',
            'director_id' => 'nullable|exists:users,id',
            'responsible_id' => 'nullable|exists:users,id',
            'type' => 'nullable|string',
        ];

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
