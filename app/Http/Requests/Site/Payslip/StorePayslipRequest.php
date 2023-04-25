<?php

namespace App\Http\Requests\Site\Payslip;

use Illuminate\Foundation\Http\FormRequest;

class StorePayslipRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'file' => 'required',
        ];
    }
}
