<?php

namespace App\Http\Requests\Site\Contractor\Document;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class EditContractorDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('update', User::class);
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
        $contractorDocument = $this->route('contractor_document');
        $isNumberChange = $contractorDocument->number !== $this->input('number');
        $isDateIssueChange = $contractorDocument->date_issue->format('d.m.Y') !== $this->input('date_issue');
        $isRelevantChange = $contractorDocument->is_relevant !== $this->input('is_relevant');
        $isPlaceChange = $contractorDocument->place !== $this->input('place');
        $isDateValidChange = false;

        if ($contractorDocument->date_valid) {
            $isDateValidChange = $contractorDocument->date_valid->format('d.m.Y') !== $this->input('date_valid');
            $this->date_valid_old = $contractorDocument->date_valid;
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
