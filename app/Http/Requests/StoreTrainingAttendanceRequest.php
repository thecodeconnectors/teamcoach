<?php

namespace App\Http\Requests;

use App\Traits\ChecksModelOwnership;
use Illuminate\Foundation\Http\FormRequest;

class StoreTrainingAttendanceRequest extends FormRequest
{
    use ChecksModelOwnership;

    public function authorize(): bool
    {
        $accountId = $this->user()->account_id;
        $playerIds = (array)$this->get('ids');

        foreach ($playerIds as $playerId) {
            if (!$this->playerBelongsToAccount($accountId, $playerId)) {
                return false;
            }
        }

        return $this->user()->can('update', $this->route('training'));
    }

    public function rules(): array
    {
        return [
            'ids' => [
                'required',
                'array',
            ],
        ];
    }
}
