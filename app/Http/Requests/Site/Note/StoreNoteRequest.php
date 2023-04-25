<?php

namespace App\Http\Requests\Site\Note;

use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreNoteRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('createNotes', User::class);
    }

    public function rules(): array
    {
        return [
            'seaman_id' => 'string',
            'text' => 'string',
            'attention' => 'boolean',
        ];
    }
}
