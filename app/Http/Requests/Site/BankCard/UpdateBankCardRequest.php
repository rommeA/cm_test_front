<?php

namespace App\Http\Requests\Site\BankCard;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBankCardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('update', User::class);
    }

    protected function prepareForValidation()
    {
        $this->merge(['number' => implode('', explode(' ', $this->number))]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'date_valid' => 'required|date',
            'date_issue' => 'required|date',
            'number'     => 'required|regex:/^\d+$/'
        ];
    }
}
