<?php

namespace App\Http\Requests\Site\Seaman;

use App\Models\SeamanPreviousService;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class PreviousServiceUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('update', [
            SeamanPreviousService::class, User::where('id', $this->get('user_id'))->first()
        ]);
    }

    public function rules(): array
    {
        return [
            'date_from' => 'required|date',
            'date_to' => 'nullable|date|after:date_from',
            'rank_id' => 'required|exists:seaman_ranks,id',
            'user_id' => 'required|exists:users,id',
            'vessel_imo' => ['required_without:vessel_mmsi', 'nullable', 'regex:/^(\d{7})$/'],
            'vessel_mmsi' => ['required_without:vessel_imo', 'nullable', 'regex:/^(\d{9})$/'],
            'vessel_name' => ['required', 'regex:/^[A-z0-9"]+((\s)?((\'|\-|\.)?([A-z0-9"])+))*$/u'],
            'vessel_deadweight' => 'required',
            'company_id' => 'nullable',
            'company_name' => ['requiredIf:company_id,false', 'regex:/^[A-z0-9"\.]+((\s)?((\'|\-|\.)?([A-z0-9"\.\s])+))*$/u'],
            'vessel_id' => 'nullable',
            'vessel_flag' => 'nullable',
            'vessel_engine_type' => 'nullable',
            'vessel_kw' => 'nullable',
            'vessel_type_id' => 'requiredIf:is_new_vessel,true|exists:vessel_types,id',
            'is_new_vessel' => 'nullable|bool',
        ];
    }

    public function messages(): array
    {
        return [
            'rank_id.required' => 'Rank is required',
            'company_name.required' => 'Crewing field is required',
            'vessel_name.required' => 'Name is required',
            'company_name.regex' => 'Company name must contain only english letters',
        ];
    }
}
