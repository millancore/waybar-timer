<?php

namespace Timer\Model;

use Timer\Enum\Status;
use Timer\Enum\Time;
use Timer\Enum\Type;

class TimerModel
{
    public function __construct(
        public string $uuid,
        public int $timestamp,
        public Type $type,
        public Status $status
    )
    {
        //
    }

    public function getMinutes() : int
    {
        return ceil( (time() - $this->timestamp) / Time::MINUTE_IN_SECONDS);
    }

    public function is(Status $status) : bool
    {
        return $this->status === $status;
    }

    public function toArray() : array
    {
        return [
            'uuid' => $this->uuid,
            'timestamp' => $this->timestamp,
            'type' => $this->type->value,
            'status' => $this->status->value
        ];
    }

}