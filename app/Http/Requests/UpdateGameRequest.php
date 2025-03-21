<?php

namespace App\Http\Requests;

use App\Traits\ChecksModelOwnership;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGameRequest extends FormRequest
{
    use ChecksModelOwnership;

    public function authorize(): bool
    {
        $accountId = $this->user()->account_id;
        $teamId = $this->get('team_id');

        return $this->teamBelongsToAccount($accountId, $teamId);
    }

    public function rules(): array
    {
        return [
            'team_id' => [
                'required',
                'integer',
                'exists:teams,id',
            ],
            'opponent_name' => [
                'required',
                'string',
                'max:100',
            ],
            'is_away_game' => [
                'nullable',
                'bool',
            ],
            'parts' => [
                'nullable',
                'integer',
            ],
            'part_duration' => [
                'nullable',
                'integer',
            ],
            'break_duration' => [
                'nullable',
                'integer',
            ],
            'start_at' => [
                'nullable',
                'date',
            ],
            'players' => [
                'array',
            ],
        ];
    }
}
