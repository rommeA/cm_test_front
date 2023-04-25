<?php

namespace App\Http\Requests\Site\Seaman;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SeamanPayslipsRequest extends FormRequest
{
    public function authorize(): bool
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    public function rules(): array
    {
        return [
            'email' => ['string', 'nullable', Rule::requiredIf($this->seaman->id === Auth::id())],
            'password' => ['string', 'nullable', Rule::requiredIf($this->seaman->id === Auth::id())],
        ];
    }
}
