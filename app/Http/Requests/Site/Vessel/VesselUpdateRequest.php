<?php

namespace App\Http\Requests\Site\Vessel;

use App\Models\Vessel;
use Illuminate\Foundation\Http\FormRequest;

class VesselUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $id = $this->get('id') ?? request()->route('vessel')?->id ;

        $vessel = Vessel::where('id', $id)->first();
        return auth()->user()->can('update', [Vessel::class, $vessel]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $id = $this->get('id') ?? request()->route('vessel')?->id ;

        return [
            'imo' =>  ['required_without:mmsi', 'nullable', 'regex:/^(\d{7})$/', 'unique:vessels,imo,' . $id],
            'mmsi' => ['required_without:imo', 'nullable', 'regex:/^(\d{9})$/', 'unique:vessels,mmsi,' . $id],
            'vessel_type_id' => 'required|exists:vessel_types,id',
            'deadweight' => 'nullable|numeric',
            'engine_type' => 'nullable|string',
            'call_sign' => 'nullable|string',
            'is_icebreaker' => 'required|boolean',
            'count_experience' => 'nullable|boolean',
            'company_id' => 'nullable|string',
            'homeport_id' => 'nullable|exists:ports,id',
            'email' => 'nullable|email',
            'extra_email' => 'nullable|email',
            'operator_id' => 'nullable|exists:users,id',
            'superintendent_id' => 'nullable|exists:users,id',
            'captain_mentor_id' => 'nullable|exists:users,id',

            'kw' => 'nullable|integer',
            'name' => ['nullable', 'regex:/^[A-z0-9"]+((\s)?((\'|\-|\.)?([A-z0-9"])+))*$/u'],
            'name_date' => 'required_with:name|nullable|date',
            'flag' => 'nullable',
            'flag_date' => 'required_with:flag|nullable|date',
        ];
    }
}
