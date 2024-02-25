<?php

namespace Feature\Http\Controllers;

use App\Models\Training;
use App\Modules\Attendance\Enums\AttendanceState;
use App\Modules\Attendance\Models\Attendance;
use Database\Factories\PlayerFactory;
use Database\Factories\TeamFactory;
use Database\Factories\TrainingFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\WithUsers;

class TrainingPlayerControllerTest extends TestCase
{
    use RefreshDatabase, WithUsers;

    public function testItAddPlayerToATrainingAttendanceList(): void
    {
        $user = $this->owner();
        $team = TeamFactory::new()->for($user->account)->create();
        $training = TrainingFactory::new()->for($user->account)->for($team)->create();
        $players = PlayerFactory::new()->count(3)->for($user->account)->create();

        $payload = [
            'ids' => $players->pluck('id')->toArray(),
        ];

        $this
            ->actingAs($user)
            ->post("api/training/{$training->id}/players", $payload)
            ->assertStatus(Response::HTTP_OK);

        $this->assertCount(3, $training->attendees()->get());
    }

    public function testItUpdatesATrainingAttendance(): void
    {
        $user = $this->owner();
        $team = TeamFactory::new()->for($user->account)->create();
        $training = TrainingFactory::new()->for($user->account)->for($team)->create();
        $players = PlayerFactory::new()->count(3)->for($user->account)->create();

        $training->createAttendanceList($players);

        $player = $players->first();

        $payload = [
            'state' => AttendanceState::Accepted->value,
        ];

        $this
            ->actingAs($user)
            ->patch("api/training/{$training->id}/players/{$player->id}", $payload)
            ->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas(Attendance::class, [
            'account_id' => $user->account_id,
            'attendable_type' => (new Training)->getMorphClass(),
            'attendable_id' => $training->id,
            'attendee_type' => $player->getMorphClass(),
            'attendee_id' => $player->getKey(),
            'state' => AttendanceState::Accepted->value,
        ]);
    }
}
