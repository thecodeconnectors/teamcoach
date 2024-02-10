<?php

namespace Feature\Http\Controllers;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\WithUsers;

class SettingsControllerTest extends TestCase
{
    use RefreshDatabase, WithUsers;

    public function testItListsTheDefaultSettings(): void
    {
        $user = $this->owner();

        $this
            ->actingAs($user)
            ->get('api/settings')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonPath('data', function ($data) {
                $this->assertEquals(config('settings'), $data);

                return true;
            });
    }

    public function testItStoresASetting(): void
    {
        $this
            ->actingAs($this->owner())
            ->post('api/settings/default_game_parts', [
                'value' => 2,
            ])
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonPath('data', function ($data) {
                $this->assertEquals([
                    'key' => 'default_game_parts',
                    'value' => 2,
                ], $data);

                return true;
            });
    }

    public function testItUpdatesASetting(): void
    {
        $user = $this->owner();

        Setting::query()->create([
            'account_id' => $user->account_id,
            'key' => 'default_game_parts',
            'value' => 1,
        ]);

        $this
            ->actingAs($user)
            ->post('api/settings/default_game_parts', [
                'value' => 2,
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonPath('data', function ($data) {
                $this->assertEquals([
                    'key' => 'default_game_parts',
                    'value' => 2,
                ], $data);

                return true;
            });
    }

    public function testItDoesNotStoreOrUpdateANonExistingSetting(): void
    {
        $this
            ->actingAs($this->owner())
            ->post('api/settings/not_allowed')
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

}
