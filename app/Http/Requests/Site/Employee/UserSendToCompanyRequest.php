<?php

namespace App\Http\Requests\Site\Employee;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserSendToCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->has('phone') and !empty($this->phone)) {
            $phone = str_replace('(', '', $this->phone);
            $phone = str_replace(')', '', $phone);
            $phone = str_replace('-', '', $phone);
            $phone = str_replace(' ', '', $phone);
            if ($phone[0] == 8) {
                $phone = substr_replace($phone, '+7', 0, 1);
            }
            $this->merge(['phone' => $phone]);
        }

        if ($this->has('extra_phone')) {
            $extra_phones = [];
            foreach ($this->extra_phone as $phone) {
                if (empty($phone)) {
                    continue;
                }
                $phone = str_replace('(', '', $phone);
                $phone = str_replace(')', '', $phone);
                $phone = str_replace('-', '', $phone);
                $phone = str_replace(' ', '', $phone);
                if ($phone[0] == 8) {
                    $phone = substr_replace($phone, '+7', 0, 1);
                }
                $extra_phones[] = $phone;
            }
            $this->merge(['extra_phone' => $extra_phones]);
        }
    }

    private $max_date;

    public function rules(): array
    {
        $id = $this->get('id') ?? (request()->route('employee')?->id ?? request()->route('user')?->id);
        $this->max_date = date('d.m.Y', strtotime("-18 years"));
        $result = [
            'email' => 'email:rfc,dns|required|unique:users,email,' . $id,
            'name' => 'required|unique:users,name,' . $id,
            'lastname' => ['required', 'regex:/^[A-z]+((\s)?((\'|\-|\.)?([A-z])+))*$/u'],
            'firstname' => ['required', 'regex:/^[A-z]+((\s)?((\'|\-|\.)?([A-z])+))*$/u'],
            'patronymic' => ['nullable', 'regex:/^[А-яЁё]+((\s)?((\'|\-|\.)?([А-яЁё])+))*$/u'],
            'date_birth' => "required|date|before_or_equal:$this->max_date",
            'phone' => 'required|regex:/^\+*[0-9]+$/',
            'extra_EMAIL.*' => 'nullable|email:rfc,dns',
            'extra_PHONE.*' => ['nullable', 'regex:/^\+*[0-9]+$/'],
            'extra_SKYPE.*' => ['nullable', 'string'],
            'extra_INTERNAL_PHONE.*' => ['nullable', 'regex:/^\+*[0-9]+$/'],
            'internal_phone' => ['nullable', 'regex:/^\d{3}$/'],
            'rank_id' => 'required|exists:seaman_ranks,id',
            'available_date' => 'required|string',
            'sex' => 'required|string',
            'marital_status' => 'required|string',
            'citizenship' => 'required|string',
            'second_citizenship' => 'nullable|string',
            'place_birth_ru' => 'required|string',
            'place_birth' => 'required|string',
            'registration_address' => 'required|string',
            'actual_address' => 'required|string',
            'homeport_id' => 'required|string',
            'jacket_size' => 'required|string',
            'trousers_size' => 'required|string',
            'shoe_size' => 'required|string',
            'height' => 'required|string',
            'weight' => 'required|string',
            'hair_color' => 'required|string',
            'eye_color' => 'required|string',
            'no_prev_serv' => 'nullable|boolean',
            'date_beginning_sea_service' => 'required_with:no_prev_serv|nullable|string',
            'company_beginning_sea_service' => 'required_with:no_prev_serv|nullable|string',
            'comment' => 'nullable|string',
            'comment_to_hr' => 'nullable|string',
            'is_archive' => 'nullable|boolean',
            'skype_login' => "nullable|string",
            'company_id' => "nullable|exists:companies,id",
            'department_id' => "nullable|exists:departments,id",
            'position_id' => "nullable|exists:positions,id",
            'date_from' => "nullable|date",
            'photo_file' => 'nullable',
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
