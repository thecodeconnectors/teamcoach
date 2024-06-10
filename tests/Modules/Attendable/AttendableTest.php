<?php

namespace Modules\Attendable;

use App\Models\Training;
use App\Modules\Attendance\Enums\AttendanceState;
use App\Modules\Attendance\Models\Attendance;
use Database\Factories\PlayerFactory;
use Database\Factories\TrainingFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\WithUsers;

class AttendableTest extends TestCase
{
    use RefreshDatabase, WithUsers;

    public function testItCreatesAnAttendanceList(): void
    {
        $user = $this->owner();
        $training = TrainingFactory::new()->for($user->account)->create();
        $players = PlayerFactory::new()->for($user->account)->count(3)->create();

        $training->createAttendanceList($players);

        foreach ($players as $player) {
            $this->assertDatabaseHas(Attendance::class, [
                'account_id' => $user->account_id,
                'attendable_type' => (new Training)->getMorphClass(),
                'attendable_id' => $training->id,
                'attendee_type' => $player->getMorphClass(),
                'attendee_id' => $player->getKey(),
                'state' => AttendanceState::Pending->value,
            ]);
        }
    }

    public function testItAcceptsTheAttendance(): void
    {
        $user = $this->owner();
        $training = TrainingFactory::new()->for($user->account)->create();
        $player = PlayerFactory::new()->for($user->account)->create();

        $training->createAttendanceList($player);
        $training->acceptAttendance($player);

        $this->assertDatabaseHas(Attendance::class, [
            'account_id' => $user->account_id,
            'attendable_type' => (new Training)->getMorphClass(),
            'attendable_id' => $training->id,
            'attendee_type' => $player->getMorphClass(),
            'attendee_id' => $player->getKey(),
            'state' => AttendanceState::Accepted->value,
        ]);
    }

    public function testItDeclinesTheAttendance(): void
    {
        $user = $this->owner();
        $training = TrainingFactory::new()->for($user->account)->create();
        $player = PlayerFactory::new()->for($user->account)->create();

        $training->createAttendanceList($player);
        $training->declineAttendance($player);

        $this->assertDatabaseHas(Attendance::class, [
            'account_id' => $user->account_id,
            'attendable_type' => (new Training)->getMorphClass(),
            'attendable_id' => $training->id,
            'attendee_type' => $player->getMorphClass(),
            'attendee_id' => $player->getKey(),
            'state' => AttendanceState::Declined->value,
        ]);
    }

}
