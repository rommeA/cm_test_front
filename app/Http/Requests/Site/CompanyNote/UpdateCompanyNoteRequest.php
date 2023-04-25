<?php

namespace App\Http\Requests\Site\CompanyNote;

use App\Models\CompanyNote;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyNoteRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('update', CompanyNote::class);
    }

    public function rules(): array
    {
        return [
            'text' => 'string',
            'attention' => 'boolean',
        ];
    }
}
