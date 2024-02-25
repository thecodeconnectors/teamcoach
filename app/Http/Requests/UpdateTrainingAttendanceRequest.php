<?php

namespace App\Http\Requests;

use App\Modules\Attendance\Enums\AttendanceState;
use App\Traits\ChecksModelOwnership;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateTrainingAttendanceRequest extends FormRequest
{
    use ChecksModelOwnership;

    public function authorize(): bool
    {
        $accountId = $this->user()->account_id;
        $training = $this->route('training');
        $player = $this->route('player');

        return $this->playerBelongsToAccount($accountId, $player->id)
            && $this->user()->can('update', $training);
    }

    public function rules(): array
    {
        return [
            'state' => [
                'required',
                new Enum(AttendanceState::class),
            ],
        ];
    }
}
