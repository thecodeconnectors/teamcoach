<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Team;
use Database\Factories\TeamFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\WithUsers;

class TeamControllerTest extends TestCase
{
    use RefreshDatabase, WithUsers;

    public function testItListsTeams(): void
    {
        $user = $this->owner();
        TeamFactory::new()->count(20)->for($user->account)->create();

        $this
            ->actingAs($user)
            ->get('api/teams?per_page=10')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonPath('data', function ($data) {
                $this->assertCount(10, $data);

                return true;
            });
    }

    public function testItDoesNotListOpponentTeams(): void
    {
        $user = $this->owner();
        TeamFactory::new()->count(5)->for($user->account)->create();
        TeamFactory::new()->count(5)->for($user->account)->create(['is_opponent' => true]);

        $this
            ->actingAs($user)
            ->get('api/teams')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonPath('data', function ($data) {
                $this->assertCount(5, $data);

                return true;
            });
    }

    public function testItOnlyListsTeamsOfTheUsersAccount(): void
    {
        $user = $this->owner();

        $userTeam = TeamFactory::new()->for($user->account)->create();
        TeamFactory::new()->create();

        $this
            ->actingAs($user)
            ->get('api/teams?per_page=10')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonPath('data', function ($data) use ($userTeam) {
                $this->assertCount(1, $data);

                $this->assertEquals($userTeam->id, $data[0]['id']);

                return true;
            });
    }

    public function testItCreatesATeam(): void
    {
        $payload = ['name' => 'Team A'];

        $this
            ->actingAs($this->owner())
            ->post('api/teams', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson(function (AssertableJson $json) use ($payload) {
                $json->where('data.name', $payload['name']);
            });

        $this->assertDatabaseHas(Team::class, $payload);
    }

    public function testItShowsATeam(): void
    {
        $user = $this->owner();
        $team = TeamFactory::new()->for($user->account)->create();

        $this
            ->actingAs($user)
            ->get("api/teams/{$team->id}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(function (AssertableJson $json) use ($team) {
                $json->where('data.name', $team->name);
            });
    }

    public function testItUpdatesATeam(): void
    {
        $user = $this->owner();
        $team = TeamFactory::new()->for($user->account)->create();

        $payload = ['name' => 'Team A'];

        $this
            ->actingAs($user)
            ->patch("api/teams/{$team->id}", $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(function (AssertableJson $json) use ($payload) {
                $json->where('data.name', $payload['name']);
            });

        $this->assertDatabaseHas(Team::class, $payload);
    }

    public function testItDeletesATeam(): void
    {
        $user = $this->owner();
        $team = TeamFactory::new()->for($user->account)->create();

        $this
            ->actingAs($user)
            ->delete("api/teams/{$team->id}")
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertSoftDeleted(Team::class, ['id' => $team->id]);
    }
}
