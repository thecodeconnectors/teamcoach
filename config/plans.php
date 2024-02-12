<?php

use App\Enums\EventType;

return [
    'free' => [
        'teams' => 1,
        'users' => 1,
        'event_types' => [
            EventType::Goal->value,
            EventType::GoalLossed->value,
        ],
    ],
];
