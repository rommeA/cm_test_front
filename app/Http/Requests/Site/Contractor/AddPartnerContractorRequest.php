<?php

namespace App\Http\Requests\Site\Contractor;

use Illuminate\Foundation\Http\FormRequest;

class AddPartnerContractorRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'partner_id' => 'required|exists:users,id',
            'position' => 'nullable|string',
        ];
    }
}
