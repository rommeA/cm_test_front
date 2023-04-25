<?php

namespace App\Http\Requests\Site\Company\Document;

use App\Models\CompanyDocument;
use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('create', CompanyDocument::class);
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
            'type_id' => 'required|exists:company_document_types,id',
            'is_relevant' => 'bool',
            'is_archive' => 'bool',
            'company_id' => 'required|exists:companies,id',
        ];
    }
}
