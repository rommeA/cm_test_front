<?php

namespace App\Http\Requests\Site\Employee;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class PreviousServiceStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('create', User::class);
    }

    protected function prepareForValidation()
    {

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date_from' => 'nullable|date',
            'date_to'   =>    'nullable|date|after:date_from',
            'position_name'     => ['nullable', 'regex:/^[A-z0-9"]+((\s)?((\'|\-|\.)?([A-z0-9"])+))*$/u'],
            'position_name_ru'   => ['nullable', 'regex:/^[А-яЁё0-9"]+((\s)?((\'|\-|\.)?([А-яЁё0-9"])+))*$/u'],
            'company_name'     => ['nullable', 'regex:/^[A-z0-9"]+((\s)?((\'|\-|\.)?([A-z0-9"])+))*$/u'],
            'company_name_ru'   => ['nullable', 'regex:/^[А-яЁё0-9"]+((\s)?((\'|\-|\.)?([А-яЁё0-9"])+))*$/u'],

        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => '',
            'name.unique' => '',
        ];
    }
}
