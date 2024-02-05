<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date_time' => [
                'nullable',
                'date',
            ],
        ];
    }
}
