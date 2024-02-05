<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Carbon;

/**
 * @mixin WithUsers
 */
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public static string $now = '2024-01-01 00:00:00';

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::parse(self::$now));
    }

    protected function setUpTraits(): void
    {
        $uses = parent::setUpTraits();

        if (isset($uses[WithUsers::class])) {
            $this->seedRoles();
        }
    }

    public function assertEqualSeconds($expected, $actual, $margin = 1): void
    {
        // When comparing seconds,
        // we sometimes get a second difference.
        // For now, we don't test on second detail.
        $this->assertTrue($expected >= $actual - $margin && $expected <= $actual + $margin, "Failed asserting that {$actual} matches expected {$expected}.");
    }
}
