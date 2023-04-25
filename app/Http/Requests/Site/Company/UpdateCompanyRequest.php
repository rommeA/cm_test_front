<?php

namespace App\Http\Requests\Site\Company;

use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('update', Company::class);
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

        if ($this->has('extra_PHONE')) {
            $extra_phones = [];
            foreach ($this->extra_PHONE as $phone) {
                if (empty($phone) || (strlen($this->phone) === 1 && $this->phone[0] === '+')) {
                    continue;
                }
                $extra_phones[] = $this->formatPhone($phone);
            }
            $this->merge(['extra_PHONE' => $extra_phones]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'country_id' => 'nullable|exists:countries,id',
            'official_address' => 'nullable|string',
            'current_address' => 'nullable|string',
            'phone' => 'nullable|regex:/^\+*[0-9]+$/',
            'email' => 'nullable|email:rfc,dns',
            'fax' => 'nullable|string',
            'UTR' => 'nullable|string',
            'KPP' => 'nullable|string',
            'OGRN' => 'nullable|string',
            'extra_EMAIL.*' => ['nullable', 'email:rfc,dns'],
            'extra_PHONE.*' => ['nullable', 'regex:/^\+*[0-9]+$/'],
            'extra_FAX.*' => ['nullable', 'string'],
        ];
    }
}
