<?php

namespace App\Http\Requests\Site\Company\Document;

use App\Models\CompanyDocument;
use Illuminate\Foundation\Http\FormRequest;

class EditCompanyDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('update', CompanyDocument::class);
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
        $companyDocument = $this->route('companyDocument');
        $isNumberChange = $companyDocument->number !== $this->input('number');
        $isDateIssueChange = $companyDocument->date_issue->format('d.m.Y') !== $this->input('date_issue');
        $isRelevantChange = $companyDocument->is_relevant !== $this->input('is_relevant');
        $isPlaceChange = $companyDocument->place !== $this->input('place');
        $isDateValidChange = false;

        if ($companyDocument->date_valid) {
            $isDateValidChange = $companyDocument->date_valid->format('d.m.Y') !== $this->input('date_valid');
            $this->date_valid_old = $companyDocument->date_valid;
        }

        $rules = [
            'number' => ['required', 'string'],
            'date_issue' => ['required', 'date', 'before_or_equal:today'],
            'date_valid' => ['nullable', 'date', 'after:date_issue'],
            'is_archive' => ['bool'],
        ];

        if ($isDateValidChange) {
            $rules['date_valid'][] = 'same:date_valid_old';
        }


        if ($isRelevantChange) {
            $rules['relevant'][] = function ($attribute, $value, $fail) {
                $fail("It's not possible to change this field. Current is '{$this->route('companyDocument')->relevant}'");
            };
        }

        if ($isPlaceChange) {
            $rules['place'][] = function ($attribute, $value, $fail) {
                $fail("It's not possible to change this field. Current is '{$this->route('companyDocument')->place}'");
            };
        }

        if ($isNumberChange && $isDateIssueChange) {
            $rules['number'][] = function ($attribute, $value, $fail) {
                $fail("You can edit only one of thees fields");
            };
            $rules['date_issue'][] = function ($attribute, $value, $fail) {
                $fail("You can edit only one of thees fields");
            };
        }

        return $rules;
    }
}
