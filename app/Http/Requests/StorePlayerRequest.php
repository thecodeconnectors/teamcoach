<?php

namespace App\Http\Requests;

use App\Enums\Position;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StorePlayerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|max:100',
            'position' => [
                'required',
                new Enum(Position::class),
            ],
            'team_id' => [
                'nullable',
                'integer',
                'exists:teams,id',
            ],
        ];
    }
}
