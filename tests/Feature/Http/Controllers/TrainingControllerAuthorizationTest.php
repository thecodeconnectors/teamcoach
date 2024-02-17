<?php

namespace Feature\Http\Controllers;

use App\Models\Training;
use App\Modules\Users\Enums\RoleType;
use Database\Factories\TrainingFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\WithUsers;

class TrainingControllerAuthorizationTest extends TestCase
{
    use RefreshDatabase, WithUsers;

    /**
     * @dataProvider roleDataProvider
     */
    public function testCreateTrainingAuthorization(RoleType $role, bool $allow): void
    {
        $user = $this->userWithRole($role);

        $payload = ['start_at' => TestCase::$now];

        $response = $this->actingAs($user)->post("api/training", $payload);

        $this->assertEquals($allow, $user->can('create', Training::class));
        $this->assertEquals($allow, $response->getStatusCode() !== Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @dataProvider roleDataProvider
     */
    public function testShowTrainingAuthorization(RoleType $role, bool $allow, bool $allowOtherAccount): void
    {
        $user = $this->userWithRole($role);
        $training = TrainingFactory::new()->for($user->account)->create();
        $otherAccountTraining = TrainingFactory::new()->create();

        $response = $this->actingAs($user)->get("api/training/{$training->id}");
        $otherAccountResponse = $this->actingAs($user)->get("api/training/{$otherAccountTraining->id}");

        $this->assertEquals($allow, $user->can('view', $training));
        $this->assertEquals($allow, $response->getStatusCode() !== Response::HTTP_FORBIDDEN, $response->getStatusCode());

        $this->assertEquals($allowOtherAccount, $user->can('view', $otherAccountTraining));
        $this->assertEquals($allowOtherAccount, $otherAccountResponse->getStatusCode() !== Response::HTTP_FORBIDDEN, $otherAccountResponse->getStatusCode());
    }

    /**
     * @dataProvider roleDataProvider
     */
    public function testUpdateTrainingAuthorization(RoleType $role, bool $allow, bool $allowOtherAccount): void
    {
        $user = $this->userWithRole($role);
        $training = TrainingFactory::new()->for($user->account)->create();
        $otherAccountTraining = TrainingFactory::new()->create();

        $response = $this->actingAs($user)->patch("api/training/{$training->id}");
        $otherAccountResponse = $this->actingAs($user)->patch("api/training/{$otherAccountTraining->id}");

        $this->assertEquals($allow, $user->can('update', $training));
        $this->assertEquals($allow, $response->getStatusCode() !== Response::HTTP_FORBIDDEN, $response->getStatusCode());

        $this->assertEquals($allowOtherAccount, $user->can('update', $otherAccountTraining));
        $this->assertEquals($allowOtherAccount, $otherAccountResponse->getStatusCode() !== Response::HTTP_FORBIDDEN, $otherAccountResponse->getStatusCode());
    }

    /**
     * @dataProvider roleDataProvider
     */
    public function testDeleteTrainingAuthorization(RoleType $role, bool $allow, bool $allowOtherAccount): void
    {
        $user = $this->userWithRole($role);
        $training = TrainingFactory::new()->for($user->account)->create();
        $otherAccountTraining = TrainingFactory::new()->create();

        $response = $this->actingAs($user)->delete("api/training/{$training->id}");
        $otherAccountResponse = $this->actingAs($user)->delete("api/training/{$otherAccountTraining->id}");

        $this->assertEquals($allow, $user->can('delete', $training));
        $this->assertEquals($allow, $response->getStatusCode() !== Response::HTTP_FORBIDDEN, $response->getStatusCode());

        $this->assertEquals($allowOtherAccount, $user->can('delete', $otherAccountTraining));
        $this->assertEquals($allowOtherAccount, $otherAccountResponse->getStatusCode() !== Response::HTTP_FORBIDDEN, $otherAccountResponse->getStatusCode());
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
