<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeamanCVRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'lastTenYears' => 'required|boolean'
        ];
    }
}
