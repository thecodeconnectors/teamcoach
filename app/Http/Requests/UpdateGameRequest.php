<?php

namespace App\Http\Requests;

use App\Modules\Partners\Models\Partner;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGameRequest extends FormRequest
{
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
