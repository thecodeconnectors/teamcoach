<?php

namespace App\Http\Requests;

use App\Traits\ChecksModelOwnership;
use Illuminate\Foundation\Http\FormRequest;

class StoreGameRequest extends FormRequest
{
    use ChecksModelOwnership;

    public function authorize(): bool
    {
        $accountId = $this->user()->account_id;
        $playerIds = $this->get('player_ids');
        $teamId = $this->get('team_id');

        foreach ($playerIds as $playerId) {
            if (!$this->playerBelongsToAccount($accountId, $playerId)) {
                return false;
            }
        }

        return $this->teamBelongsToAccount($accountId, $teamId);
    }

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
