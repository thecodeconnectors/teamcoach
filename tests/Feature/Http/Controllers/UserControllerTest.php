<?php

namespace Feature\Http\Controllers;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\WithUsers;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithUsers;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('plans.free.users', 2);
    }

    public function testItListsUsers(): void
    {
        $user = $this->owner();
        UserFactory::new()->count(20)->for($user->account)->create();

        $this
            ->actingAs($user)
            ->get('api/users?per_page=10')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonPath('data', function ($data) {
                $this->assertCount(10, $data);

                return true;
            });
    }

    public function testItOnlyListsUsersOfTheUsersAccount(): void
    {
        $user = $this->owner();

        UserFactory::new()->create();

        $this
            ->actingAs($user)
            ->get('api/users?per_page=10')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonPath('data', function ($data) use ($user) {
                $this->assertCount(1, $data);
                $this->assertEquals($user->id, $data[0]['id']);

                return true;
            });
    }

    public function testItCreatesAUser(): void
    {
        $payload = [
            'name' => 'User A',
            'email' => 'test@test.nl',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ];

        $this
            ->actingAs($this->owner())
            ->post('api/users', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson(function (AssertableJson $json) use ($payload) {
                $json->where('data.name', $payload['name']);
            });

        $this->assertDatabaseHas(User::class, [
            'name' => 'User A',
            'email' => 'test@test.nl',
        ]);
    }

    public function testItShowsAUser(): void
    {
        $acting = $this->owner();
        $user = UserFactory::new()->for($acting->account)->create();

        $this
            ->actingAs($acting)
            ->get("api/users/{$user->id}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(function (AssertableJson $json) use ($user) {
                $json->where('data.name', $user->name);
            });
    }

    public function testItUpdatesAUser(): void
    {
        $acting = $this->owner();
        $user = UserFactory::new()->for($acting->account)->create();

        $payload = ['name' => 'User A'];

        $this
            ->actingAs($acting)
            ->patch("api/users/{$user->id}", $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(function (AssertableJson $json) use ($payload) {
                $json->where('data.name', $payload['name']);
            });

        $this->assertDatabaseHas(User::class, $payload);
    }

    public function testItDeletesAUser(): void
    {
        $acting = $this->owner();
        $user = UserFactory::new()->for($acting->account)->create();

        $this
            ->actingAs($acting)
            ->delete("api/users/{$user->id}")
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertSoftDeleted(User::class, ['id' => $user->id]);
    }
}
