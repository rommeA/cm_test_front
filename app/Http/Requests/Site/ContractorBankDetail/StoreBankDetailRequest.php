<?php

namespace App\Http\Requests\Site\ContractorBankDetail;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreBankDetailRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'contractor_id' => 'required|exists:contractors,id',
            'bank_name' => 'required|string',
            'RCBIC' => 'required|string',
            'account' => 'required|string',
            'correspondent_account' => 'required|string',
            'SWIFT' => 'required|string',
            'currency_id' => 'required|exists:currencies,id',
        ];
    }
}
