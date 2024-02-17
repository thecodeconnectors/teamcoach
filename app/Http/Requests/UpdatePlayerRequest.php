<?php

namespace App\Http\Requests;

use App\Enums\Position;
use App\Traits\ChecksModelOwnership;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdatePlayerRequest extends FormRequest
{
    use ChecksModelOwnership;

    public function authorize(): bool
    {
        return $this->teamBelongsToAccount($this->user()->account_id, $this->get('team_id'));
    }
    
    public function rules(): array
    {
        return [
            'name' => 'required|max:100',
            'avatar' => 'nullable|max:100',
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
