<?php

namespace App\Http\Requests\Site\Payslip;

use App\Models\Payslip;
use Illuminate\Foundation\Http\FormRequest;

class IndexPayslipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('viewAny', Payslip::class);
    }

    public function rules(): array
    {
        return [
            'vessel_id' => 'nullable|exists:vessels,id',
            'date' => 'nullable|string',
        ];
    }
}
