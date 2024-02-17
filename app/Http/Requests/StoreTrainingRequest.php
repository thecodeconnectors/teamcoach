<?php

namespace App\Http\Requests;

use App\Traits\ChecksModelOwnership;
use Illuminate\Foundation\Http\FormRequest;

class StoreTrainingRequest extends FormRequest
{
    use ChecksModelOwnership;

    public function authorize(): bool
    {
        return $this->teamBelongsToAccount($this->user()->account_id, $this->get('team_id'));
    }

    public function rules(): array
    {
        return [
            'start_at' => 'required|date',
            'team_id' => [
                'required',
                'integer',
                'exists:teams,id',
            ],
        ];
    }
}
