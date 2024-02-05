<?php

namespace Tests\Unit\Enums;

use App\Enums\EventType;
use Tests\TestCase;

class EventTypeTest extends TestCase
{
    public function testItIsADurationEventType(): void
    {
        $this->assertTrue(EventType::isDurationEventType(EventType::GameBreak));
        $this->assertTrue(EventType::isDurationEventType(EventType::PlayTime));
        $this->assertTrue(EventType::isDurationEventType(EventType::GameBreak->value));
        $this->assertTrue(EventType::isDurationEventType(EventType::PlayTime->value));
    }

    public function testItIsNotADurationEventType(): void
    {
        $this->assertFalse(EventType::isDurationEventType());
        $this->assertFalse(EventType::isDurationEventType(EventType::Assist));
        $this->assertFalse(EventType::isDurationEventType(EventType::Assist->value));
    }
}
