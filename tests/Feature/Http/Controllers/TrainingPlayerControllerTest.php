<?php

namespace Feature\Http\Controllers;

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

    public function testItAddPlayerToATrainingAttendenceList(): void
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
}
