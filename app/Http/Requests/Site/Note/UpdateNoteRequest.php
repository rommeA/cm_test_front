<?php

namespace App\Http\Requests\Site\Note;

use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateNoteRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('createNotes', User::class);
    }

    public function rules(): array
    {
        return [
            'text' => 'string',
            'attention' => 'boolean',
        ];
    }
}
