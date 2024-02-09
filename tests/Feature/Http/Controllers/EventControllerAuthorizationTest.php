<?php

namespace Feature\Http\Controllers;

use App\Enums\EventType;
use App\Models\Event;
use App\Modules\Users\Enums\RoleType;
use Database\Factories\EventFactory;
use Database\Factories\GameFactory;
use Database\Factories\PlayerFactory;
use Database\Factories\TeamFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\WithUsers;

class EventControllerAuthorizationTest extends TestCase
{
    use RefreshDatabase, WithUsers;

    /**
     * @dataProvider roleDataProvider
     */
    public function testCreateEventAuthorization(RoleType $role, bool $allow): void
    {
        $user = $this->userWithRole($role);
        $team = TeamFactory::new()->for($user->account)->create();
        $opponent = TeamFactory::new()->for($user->account)->create();
        $game = GameFactory::new()->for($user->account)->for($team, 'team')->for($opponent, 'opponent')->create();
        $player = PlayerFactory::new()->for($user->account)->for($game->team)->create();

        $game->addPlayer($player);

        $payload = [
            'type' => EventType::Goal->value,
            'player_id' => $player->id,
            'team_id' => $player->team_id,
            'game_id' => $game->id,
        ];

        $response = $this->actingAs($user)->post("api/events", $payload);

        $this->assertEquals($allow, $user->can('create', Event::class));
        $this->assertEquals($allow, $response->getStatusCode() !== Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @dataProvider roleDataProvider
     */
    public function testUpdateEventAuthorization(RoleType $role, bool $allow, bool $allowOtherAccount): void
    {
        $user = $this->userWithRole($role);
        $event = EventFactory::new()->for($user->account)->create();
        $otherAccountEvent = EventFactory::new()->create();

        $response = $this->actingAs($user)->patch("api/events/{$event->id}");

        $this->assertEquals($allow, $user->can('update', $event));
        $this->assertEquals($allow, $response->getStatusCode() !== Response::HTTP_FORBIDDEN, $response->getStatusCode());
        $this->assertEquals($allowOtherAccount, $user->can('delete', $otherAccountEvent));
    }

    /**
     * @dataProvider roleDataProvider
     */
    public function testDeleteEventAuthorization(RoleType $role, bool $allow, bool $allowOtherAccount): void
    {
        $user = $this->userWithRole($role);
        $event = EventFactory::new()->for($user->account)->create();
        $otherAccountEvent = EventFactory::new()->create();

        $response = $this->actingAs($user)->delete("api/events/{$event->id}");

        $this->assertEquals($allow, $user->can('delete', $event));
        $this->assertEquals($allow, $response->getStatusCode() !== Response::HTTP_FORBIDDEN, $response->getStatusCode());
        $this->assertEquals($allowOtherAccount, $user->can('delete', $otherAccountEvent));
    }

    public static function roleDataProvider(): array
    {
        return [
            'user' => [
                'role' => RoleType::User,
                'allow' => false,
                'allowOtherAccount' => false,
            ],
            'owner' => [
                'role' => RoleType::Owner,
                'allow' => true,
                'allowOtherAccount' => false,
            ],
            'admin' => [
                'role' => RoleType::Admin,
                'allow' => true,
                'allowOtherAccount' => true,
            ],
        ];
    }
}
