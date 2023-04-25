<?php

namespace App\Http\Requests\Site\Seaman;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class AvailableDateUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('update', [User::class, User::where('id', $this->get('id'))->first()]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'available_date' => 'required|string'
        ];
    }
}
