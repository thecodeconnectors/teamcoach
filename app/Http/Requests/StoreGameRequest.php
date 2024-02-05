<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGameRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'team_id' => [
                'nullable',
                'integer',
                'exists:teams,id',
            ],
            'opponent_name' => [
                'required',
                'string',
                'max:100',
            ],
            'start_at' => [
                'nullable',
                'date',
            ],
            'player_ids' => [
                'array',
                'exists:players,id',
            ],
            'player_ids.*' => 'integer',
        ];
    }
}
