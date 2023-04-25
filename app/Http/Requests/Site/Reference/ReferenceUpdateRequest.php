<?php

namespace App\Http\Requests\Site\Reference;

use Illuminate\Foundation\Http\FormRequest;

class ReferenceUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string',
            'contact' => 'required|string',
            'vessel' => 'nullable|string',
            'crewing' => 'nullable|string',
            'comment' => 'nullable|string',

        ];
    }

    public function messages(): array
    {
        return [
            'name' => 'Name field is required',
            'contact' => 'Contact field is required',
        ];
    }
}
