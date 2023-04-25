<?php

namespace App\Http\Requests\Site\Employee;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('create', User::class);
    }

    protected function formatPhone($phone): array|string
    {
        $new_phone = str_replace('(', '', $phone);
        $new_phone = str_replace(')', '', $new_phone);
        return str_replace('+', '', $new_phone);
    }

    protected function prepareForValidation()
    {
        if ( $this->has('phone') && !empty($this->phone) &&
            !(strlen($this->phone) === 1 && $this->phone[0] === '+')
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

        if ($this->has('email')) {
            $this->merge(['name' => $this->email]);
        }
        $random_passw = base64_encode(random_bytes(32));
        $this->merge(['password' => $random_passw]);

        if ($this->has('consent_personal_data')) {
            $this->merge(['consent_personal_data' => true]);
        } else {
            $this->merge(['consent_personal_data' => false]);
        }
    }

    private $max_date;

    public function rules(): array
    {
        $this->max_date = date('d.m.Y', strtotime("-18 years"));
        $date_birth_rules = "required|date|before_or_equal:$this->max_date";
        if ($this->get('employee_type') === config('enums.employee_type.partners')) {
            $date_birth_rules = "nullable|date|before_or_equal:$this->max_date";
        }
        return [
            'email' => 'email:rfc,dns|required|iunique:users,email',
            'extra_email.*' => 'nullable|email:rfc,dns',
            'name' => 'nullable|unique:users,name',
            'lastname' => ['required', 'regex:/^[A-z]+((\s)?((\'|\-|\.)?([A-z])+))*$/u'],
            'firstname' => ['required', 'regex:/^[A-z]+((\s)?((\'|\-|\.)?([A-z])+))*$/u'],
            'lastname_ru' => ['required', 'regex:/^[А-яЁё]+((\s)?((\'|\-|\.)?([А-яЁё])+))*$/u'],
            'firstname_ru' => ['required', 'regex:/^[А-яЁё]+((\s)?((\'|\-|\.)?([А-яЁё])+))*$/u'],
            'patronymic' => ['nullable', 'regex:/^[А-яЁё]+((\s)?((\'|\-|\.)?([А-яЁё])+))*$/u'],
            'employee_type' => 'nullable|string',
            'is_seaman' => 'nullable|boolean',
            'date_birth' => $date_birth_rules,
            'phone' => ['nullable', 'regex:/^\+*[0-9]+$/'],
            'extra_phone.*' => ['nullable', 'regex:/^\+*[0-9]+$/'],
            'internal_phone' => ['nullable', 'regex:/^\d{3}$/'],
            'extra_internal_phone.*' => ['nullable', 'regex:/^\d{3}$/'],
            'skype_login' => 'nullable|string',
            'extra_skype_login.*' => 'nullable',
            'is_archive' => 'nullable|boolean',
            'place_birth_ru' => 'nullable|string',
            'place_birth' => 'nullable|string',
            'citizenship' => 'nullable|string',
            'sex' => 'nullable|string',
            'marital_status' => 'nullable|string',
            'company_id' => "nullable|exists:companies,id",
            'department_id' => "nullable|exists:departments,id",
            'position_id' => "nullable|exists:positions,id",
            'date_from' => "nullable|date",
            'registration_address' => 'nullable|string',
            'actual_address' => 'nullable|string',
            'jacket_size' => 'nullable|string',
            'trousers_size' => 'nullable|string',
            'shoe_size' => 'nullable|string',
            'height' => 'nullable|string',
            'weight' => 'nullable|string',
            'hair_color' => 'nullable|string',
            'eye_color' => 'nullable|string',
            'comment' => 'nullable|string',
            'password' => 'nullable',
            'homeport_id' => 'nullable|exists:ports,id',
            'extra_SKYPE.*' => ['nullable', 'string'],
            'extra_PHONE.*' => ['nullable', 'regex:/^\+*[0-9]+$/'],
            'extra_INTERNAL_PHONE.*' => ['nullable', 'regex:/^\+*[0-9]+$/'],
            'extra_EMAIL.*' => ['nullable', 'email:rfc,dns'],
            'photo_file' => 'nullable',
            'rank_id' => "nullable|exists:seaman_ranks,id",
            'available_date' => 'nullable|date',
            'second_citizenship' => 'nullable|string',
            'position_partner' => 'nullable|string',
            'consent_personal_data' => 'nullable|boolean',
            'country_id' => 'nullable|exists:countries,id',
            'contractor_id' => 'nullable|exists:contractors,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '',
            'name.unique' => ''
        ];
    }
}
