<?php

namespace Modules\Users;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\WithUsers;

class AvatarControllerTest extends TestCase
{
    use RefreshDatabase, WithUsers;

    public function testItReturnsAListOfAvatars(): void
    {
        $this
            ->actingAs($this->owner())
            ->get('api/avatars')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonPath('data', function ($data) {
                $this->assertCount(count(config('avatars.avatars')), $data);

                return true;
            });
    }
}
