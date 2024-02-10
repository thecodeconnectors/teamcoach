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
        $playerIdA = $this->get('player_id_a');
        $playerIdB = $this->get('player_id_b');
        $game = $this->route('game');

        return $this->gameBelongsToAccount($accountId, $game->id)
            && $this->playerBelongsToAccount($accountId, $playerIdA)
            && $this->playerBelongsToAccount($accountId, $playerIdB)
            && $this->playerBelongsToGame($game->id, $playerIdA)
            && $this->playerBelongsToGame($game->id, $playerIdB);
    }

    public function rules(): array
    {
        return [
            'player_id_a' => [
                'required',
                'integer',
                'exists:players,id',
            ],
            'player_id_b' => [
                'required',
                'integer',
                'different:player_id_a',
                'exists:players,id',
            ],
        ];
    }
}
