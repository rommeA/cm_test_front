<?php

namespace App\Http\Requests\Site\Company;

use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;

class ArchiveCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('update', Company::class);
    }

    public function rules(): array
    {
        return [
            'comment' => 'required|string|max:255',
            'attention' => 'required|boolean',
        ];
    }
}
