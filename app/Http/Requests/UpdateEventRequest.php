<?php

namespace App\Http\Requests;

use App\Enums\EventType;
use App\Traits\ChecksModelOwnership;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateEventRequest extends FormRequest
{
    use ChecksModelOwnership;
    
    public function authorize(): bool
    {
        $accountId = $this->user()->account_id;
        $playerId = $this->get('player_id');
        $gameId = $this->get('game_id');

        return $this->gameBelongsToAccount($accountId, $gameId)
            && $this->playerBelongsToGame($gameId, $playerId);
    }

    public function rules(): array
    {
        return [
            'player_id' => [
                'nullable',
                'integer',
                'exists:players,id',
            ],
            'game_id' => [
                'required',
                'integer',
                'exists:games,id',
            ],
            'type' => [
                'nullable',
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
