<?php

namespace Timer\Enum;

enum Status: int
{
    // Counter Statuses
    case RUNNING = 1;
    case STOPPED = 2;
    case PAUSED = 3;


    // Timer Statuses
    case WORKING = 4;
    case BREAK = 5;

    public function label(): string
    {
        return match ($this) {
            Status::RUNNING => 'Running',
            Status::STOPPED => 'Stopped',
            Status::PAUSED => 'Pause',
            Status::WORKING => 'Working',
            Status::BREAK => 'Break',
        };
    }
}