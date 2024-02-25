<?php

namespace Feature\Http\Controllers;

use App\Models\Training;
use Database\Factories\TeamFactory;
use Database\Factories\TrainingFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\WithUsers;

class TrainingControllerTest extends TestCase
{
    use RefreshDatabase, WithUsers;

    public function testItListsTrainings(): void
    {
        $user = $this->owner();
        TrainingFactory::new()->count(20)->for($user->account)->create();

        $this
            ->actingAs($user)
            ->get('api/training?per_page=10')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonPath('data', function ($data) {
                $this->assertCount(10, $data);

                return true;
            });
    }

    public function testItOnlyListsTrainingsOfTheUsersAccount(): void
    {
        $user = $this->owner();

        $userTraining = TrainingFactory::new()->for($user->account)->create();
        TrainingFactory::new()->create();

        $this
            ->actingAs($user)
            ->get('api/training?per_page=10')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonPath('data', function ($data) use ($userTraining) {
                $this->assertCount(1, $data);

                $this->assertEquals($userTraining->id, $data[0]['id']);

                return true;
            });
    }

    public function testItCreatesATraining(): void
    {
        $user = $this->owner();
        $team = TeamFactory::new()->for($user->account)->create();

        $payload = [
            'team_id' => $team->id,
            'start_at' => TestCase::$now,
        ];

        $this
            ->actingAs($user)
            ->post('api/training', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson(function (AssertableJson $json) use ($payload) {
                $json->where('data.start_at', $payload['start_at']);
            });

        $this->assertDatabaseHas(Training::class, $payload);
    }

    public function testItShowsATraining(): void
    {
        $user = $this->owner();
        $training = TrainingFactory::new()->for($user->account)->create(['start_at' => TestCase::$now]);

        $this
            ->actingAs($user)
            ->get("api/training/{$training->id}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(function (AssertableJson $json) use ($training) {
                $json->where('data.start_at', $training->start_at->format('Y-m-d H:i:s'));
            });
    }

    public function testItUpdatesATraining(): void
    {
        $user = $this->owner();
        $team = TeamFactory::new()->for($user->account)->create();
        $training = TrainingFactory::new()->for($user->account)->for($team)->create(['start_at' => TestCase::$now]);

        $payload = [
            'team_id' => $team->id,
            'start_at' => TestCase::$now,
        ];

        $this
            ->actingAs($user)
            ->patch("api/training/{$training->id}", $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(function (AssertableJson $json) use ($payload) {
                $json->where('data.start_at', $payload['start_at']);
            });

        $this->assertDatabaseHas(Training::class, $payload);
    }

    public function testItDeletesATraining(): void
    {
        $user = $this->owner();
        $training = TrainingFactory::new()->for($user->account)->create();

        $this
            ->actingAs($user)
            ->delete("api/training/{$training->id}")
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertSoftDeleted(Training::class, ['id' => $training->id]);
    }
}
