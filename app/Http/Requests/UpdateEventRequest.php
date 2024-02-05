<?php

namespace App\Http\Requests;

use App\Enums\EventType;
use App\Modules\Partners\Enums\AccountGroupCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateEventRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'player_id' => [
                'required',
                'integer',
                'exists:players,id',
            ],
            'team_id' => [
                'required',
                'integer',
                'exists:teams,id',
            ],
            'type' => [
                'required',
                new Enum(EventType::class),
            ],
            'started_at' => [
                'nullable',
                'date',
            ],
            'finished_at' => [
                'nullable',
                'date',
            ],
        ];
    }
}
