<?php

namespace App\Http\Requests\Site\Questionnaire;

use Illuminate\Foundation\Http\FormRequest;

class QuestionnaireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'answer' => 'required|boolean',
            'comment' => 'nullable|string',
        ];
    }
}
