<?php

namespace App\Http\Requests\Site\Document;

use App\Models\Document;
use App\Models\DocumentType;
use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('create', Document::class);
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
        $rules = [
            'number' => 'required|string',
            'date_issue' => 'required|date|before_or_equal:today',
            'date_valid' => 'nullable|date|after:date_issue',
            'place' => 'required|string',
            'document_type_id' => 'required|exists:document_types,id',
            'user_id' => 'required|exists:users,id',
            'is_relevant' => 'bool',
            'is_archive' => 'bool',
            'priority' => 'nullable|integer'
        ];

        $documentType = DocumentType::where('id', $this->document_type_id)->first();
        $extraFields = $documentType->extraDocumentFields;

        foreach ($extraFields as $extraField) {
            $rules = $extraField->is_required
                    ? array_merge($rules, ["$extraField->slug" => 'required|string'])
                    : array_merge($rules, ["$extraField->slug" => 'nullable|string']);
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'institution' => 'The Institution is required',
            'specialization' => 'The Specialization is required',
        ];
    }
}
