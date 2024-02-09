<?php

namespace App\Http\Requests;

use App\Traits\ChecksModelOwnership;
use Illuminate\Foundation\Http\FormRequest;

class SwitchGamePlayerRequest extends FormRequest
{
    use ChecksModelOwnership;

    public function authorize(): bool
    {
        $accountId = $this->user()->account_id;
        $playerId = $this->get('player_id');
        $substituteId = $this->get('substitute_id');
        $game = $this->route('game');

        return $this->gameBelongsToAccount($accountId, $game->id)
            && $this->playerBelongsToAccount($accountId, $playerId)
            && $this->playerBelongsToAccount($accountId, $substituteId)
            && $this->playerBelongsToGame($game->id, $playerId)
            && $this->playerBelongsToGame($game->id, $substituteId);
    }

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
