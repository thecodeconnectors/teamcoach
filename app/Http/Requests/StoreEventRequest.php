<?php

namespace App\Http\Requests;

use App\Enums\EventType;
use App\Traits\ChecksModelOwnership;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreEventRequest extends FormRequest
{
    use ChecksModelOwnership;

    public function authorize(): bool
    {
        $accountId = $this->user()->account_id;
        $playerId = $this->get('player_id');
        $teamId = $this->get('team_id');
        $gameId = $this->get('game_id');

        return $this->teamBelongsToAccount($accountId, $teamId)
            && $this->gameBelongsToAccount($accountId, $gameId)
            && $this->playerBelongsToGame($gameId, $playerId);
    }

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
            'game_id' => [
                'required',
                'integer',
                'exists:games,id',
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
