<?php

namespace App\Http\Requests\Site\Vessel;

use App\Models\Vessel;
use Illuminate\Foundation\Http\FormRequest;

class VesselCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('create', Vessel::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'imo' =>  ['required_without:mmsi', 'nullable', 'regex:/^(\d{7})$/', 'unique:vessels,imo'],
            'mmsi' => ['required_without:imo', 'nullable', 'regex:/^(\d{9})$/', 'unique:vessels,mmsi'],
            'vessel_type_id' => 'required|exists:vessel_types,id',
            'deadweight' => 'nullable|numeric',
            'engine_type' => 'nullable|string',
            'call_sign' => 'nullable|string',
            'is_icebreaker' => 'required|boolean',
            'count_experience' => 'required|boolean',
            'company_id' => 'nullable|string',
            'homeport_id' => 'nullable|exists:ports,id',
            'email' => 'nullable|email',
            'extra_email' => 'nullable|email',
            'operator_id' => 'nullable|exists:users,id',
            'superintendent_id' => 'nullable|exists:users,id',
            'captain_mentor_id' => 'nullable|exists:users,id',

            'kw' => 'nullable|integer',
            'name' => ['required', 'regex:/^[A-z0-9"]+((\s)?((\'|\-|\.)?([A-z0-9"])+))*$/u'],
            'flag' => 'nullable',
            'is_external' => 'required|boolean',
        ];
    }
}
