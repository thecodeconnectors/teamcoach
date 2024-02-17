<?php

namespace App\Modules\Attendance\Traits;

use App\Contract\BelongsToAccount;
use App\Modules\Attendance\Enums\AttendanceState;
use App\Modules\Attendance\Models\Attendance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;

/**
 * @property \Illuminate\Database\Eloquent\Collection<Attendance> $attendees
 */
trait Attendable
{
    public function attendees(): MorphMany
    {
        return $this->morphMany(Attendance::class, 'attendable');
    }

    public function createAttendanceList(Collection|Model $attendees): static
    {
        $attendees = $attendees instanceof Model ? collect([$attendees]) : $attendees;

        /** @var Model $attendee */
        foreach ($attendees as $attendee) {
            Attendance::query()->firstOrCreate([
                'account_id' => $attendee->account_id,
                'attendable_type' => $this->getMorphClass(),
                'attendable_id' => $this->getKey(),
                'attendee_type' => $attendee->getMorphClass(),
                'attendee_id' => $attendee->getKey(),
            ]);
        }

        return $this;
    }

    public function acceptAttendance(Model|BelongsToAccount $attendee): Model|BelongsToAccount
    {
        return $this->updateAttendenceState($attendee, AttendanceState::Accepted);
    }

    public function declineAttendance(Model|BelongsToAccount $attendee): Model|BelongsToAccount
    {
        return $this->updateAttendenceState($attendee, AttendanceState::Declined);
    }

    private function updateAttendenceState(Model|BelongsToAccount $attendee, AttendanceState $state): Model|BelongsToAccount
    {
        /** @var Model $attendable */
        $attendable = $this->attendees()->where([
            'account_id' => $attendee->account_id,
            'attendable_type' => $this->getMorphClass(),
            'attendable_id' => $this->getKey(),
            'attendee_type' => $attendee->getMorphClass(),
            'attendee_id' => $attendee->getKey(),
        ])->first();

        $attendable->update(['state' => $state]);

        return $attendable;
    }

}
