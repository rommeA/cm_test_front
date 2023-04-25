<?php

namespace App\Http\Requests\Site\Employee;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
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

    private $max_date;

    public function rules(): array
    {
        $id = $this->get('id') ?? (request()->route('employee')?->id ?? request()->route('user')?->id);
        $this->max_date = date('d.m.Y', strtotime("-18 years"));
        $date_birth_rules = "required|date|before_or_equal:$this->max_date";
        if ($this->get('employee_type') == config('enums.employee_type.partners')) {
            $date_birth_rules = "nullable|date|before_or_equal:$this->max_date";
        }
        $result = [
            'email' => 'email:rfc,dns|required|iunique:users,email,' . $id,
            'name' => 'nullable|unique:users,name,' . $id,
            'lastname' => ['required', 'regex:/^[A-z]+((\s)?((\'|\-|\.)?([A-z])+))*$/u'],
            'firstname' => ['required', 'regex:/^[A-z]+((\s)?((\'|\-|\.)?([A-z])+))*$/u'],
            'patronymic' => ['nullable', 'regex:/^[А-яЁё]+((\s)?((\'|\-|\.)?([А-яЁё])+))*$/u'],
            'date_birth' => $date_birth_rules,
            'phone' => 'nullable|regex:/^\+*[0-9]+$/',
            'extra_EMAIL.*' => 'nullable|email:rfc,dns',
            'extra_PHONE.*' => ['nullable', 'regex:/^\+*[0-9]+$/'],
            'extra_SKYPE.*' => ['nullable', 'string'],
            'extra_INTERNAL_PHONE.*' => ['nullable', 'regex:/^\+*[0-9]+$/'],
            'internal_phone' => ['nullable', 'regex:/^\d{3}$/'],
            'rank_id' => 'nullable|exists:seaman_ranks,id',
            'available_date' => 'nullable|string',
            'sex' => 'nullable|string',
            'marital_status' => 'nullable|string',
            'citizenship' => 'nullable|string',
            'second_citizenship' => 'nullable|string',
            'place_birth_ru' => 'nullable|string',
            'place_birth' => 'nullable|string',
            'registration_address' => 'nullable|string',
            'actual_address' => 'nullable|string',
            'homeport_id' => 'nullable|string',
            'jacket_size' => 'nullable|string',
            'trousers_size' => 'nullable|string',
            'shoe_size' => 'nullable|string',
            'height' => 'nullable|string',
            'weight' => 'nullable|string',
            'hair_color' => 'nullable|string',
            'eye_color' => 'nullable|string',
            'date_beginning_sea_service' => 'nullable|string',
            'company_beginning_sea_service' => 'nullable|string',
            'comment' => 'nullable|string',
            'comment_to_hr' => 'nullable|string',
            'is_archive' => 'nullable|boolean',
            'skype_login' => "nullable|string",
            'company_id' => "nullable|exists:companies,id",
            'department_id' => "nullable|exists:departments,id",
            'position_id' => "nullable|exists:positions,id",
            'date_from' => "nullable|date",
            'photo_file' => 'nullable',
            'position_partner' => 'nullable|string',
            'consent_personal_data' => 'nullable|boolean',
            'country_id' => 'nullable|exists:countries,id',
            'contractor_id' => 'nullable|exists:contractors,id',
        ];

        if (User::where('id', $this->get('id'))->first()?->employee_type !== config('enums.employee_type.seaman_applicants')) {
            $result['lastname_ru'] = ['required', 'regex:/^[А-яЁё]+((\s)?((\'|\-|\.)?([А-яЁё])+))*$/u'];
            $result['firstname_ru'] = ['required', 'regex:/^[А-яЁё]+((\s)?((\'|\-|\.)?([А-яЁё])+))*$/u'];
        }

        return $result;
    }

    public function messages(): array
    {
        return [];
    }
}
