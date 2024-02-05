<?php

namespace App\Enums;

use App\Modules\PurchaseOrders\Enums\ReasonCode;

enum EventType: string
{
    /**
     * Break between Game periods.
     */
    case GameBreak = 'game-break';

    /*
     * Play time of a Player.
     */
    case PlayTime = 'play-time';

    /*
     * Substituted Player.
     */
    case Substituted = 'substituted';

    /**
     * Goal made by a Player, can be the Player of the guest Team.
     */
    case Goal = 'goal';

    /**
     * Assist of a Player.
     */
    case Assist = 'assist';

    /**
     * Goal stopped by any Player.
     */
    case GoalStopped = 'goal-stopped';

    /**
     * Goal lossed by any Player.
     */
    case GoalLossed = 'goal-lossed';

    /**
     * Important defensive action
     */
    case DefensiveAction = 'defensive-action';

    /**
     * Duration Event Types have a start and possible end date, and can be measured in seconds.
     *
     * @return \App\Enums\EventType[]
     */
    public static function durationEventTypes(): array
    {
        return [
            self::GameBreak->value => self::GameBreak->name,
            self::PlayTime->value => self::PlayTime->name,
        ];
    }

    public static function playerActionEventTypes(): array
    {
        return [
            self::Goal->value => self::Goal->name,
            self::GoalStopped->value => self::GoalStopped->name,
            self::GoalLossed->value => self::GoalLossed->name,
            self::Assist->value => self::Assist->name,
            self::DefensiveAction->value => self::DefensiveAction->name,
        ];
    }

    public static function isDurationEventType(null|string|EventType $eventType = null): bool
    {
        $value = $eventType instanceof self ? $eventType->value : $eventType;

        return $value && isset(self::durationEventTypes()[$value]);
    }
}
