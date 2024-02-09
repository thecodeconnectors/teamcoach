<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GameStopWatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('game'));
    }

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
