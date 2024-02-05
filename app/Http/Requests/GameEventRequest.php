<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GameEventRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'player_id' => [
                'nullable',
                'integer',
                'exists:players,id',
            ],
            'team_id' => [
                'nullable',
                'integer',
                'exists:teams,id',
            ],
            'event' => [
                'required',
                'integer',
                'different:player_id',
                'exists:players,id',
            ],
        ];
    }
}
