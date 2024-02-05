<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SwitchGamePlayerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'player_id' => [
                'required',
                'integer',
                'exists:players,id',
            ],
            'substitute_id' => [
                'required',
                'integer',
                'different:player_id',
                'exists:players,id',
            ],
        ];
    }
}
