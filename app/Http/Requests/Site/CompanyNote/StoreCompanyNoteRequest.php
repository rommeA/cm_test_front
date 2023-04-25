<?php

namespace App\Http\Requests\Site\CompanyNote;

use App\Models\CompanyNote;
use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyNoteRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('create', CompanyNote::class);
    }

    public function rules(): array
    {
        return [
            'company_id' => 'required|exists:companies,id',
            'text' => 'required|string',
            'attention' => 'required|boolean',
        ];
    }
}
