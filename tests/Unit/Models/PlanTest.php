<?php

namespace Tests\Unit\Models;

use App\Models\Plan;
use Tests\TestCase;

class PlanTest extends TestCase
{
    public function testGetEventTypes(): void
    {
        $plan = new Plan('free');

        $this->assertCount(3, $plan->eventTypes());
    }

}
