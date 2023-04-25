<?php

namespace App\Http\Requests\Site\Certificate;

use App\Models\VesselCertificate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCertificateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('update', VesselCertificate::class);
    }

    protected function prepareForValidation()
    {
        if ($this->has('is_relevant')) {
            $this->merge(['is_relevant' => true]);
        } else {
            $this->merge(['is_relevant' => false]);
        }
    }

    public function rules(): array
    {
        return [
            'number' => 'required|string',
            'date_issue' => 'required|date|before_or_equal:today',
            'date_valid' => 'nullable|date|after:date_issue',
            'place' => 'required|string',
            'type_id' => 'required|exists:vessel_certificate_types,id',
            'is_relevant' => 'bool',
            'is_archive' => 'bool',
            'vessel_id' => 'required|exists:vessels,id',
        ];
    }
}
