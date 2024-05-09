<?php

namespace Timer;

use Timer\Enum\Time;
use Timer\Model\TimerModel;
use Timer\Enum\Status;
use Timer\Enum\Type;
use Timer\Repository\TimerRepository;

class Timer
{
    const WORKING_TIME = 50;
    const BREAK_TIME = 10;

    public TimerModel $instance;

    public function __construct(
        private readonly TimerRepository $repo
    )
    {
        $this->init();
    }

    private function init() : void
    {
        $timer = $this->repo->getCurrentTimer();

        if (!$timer) {
            $timer = $this->repo->persist($this->newTimer());
        }

        $this->instance = $timer;
    }

    public function getStatus() : Status
    {
        return  $this->instance->status;
    }

    public function getTimeMinutesSoFar() : int
    {
        $this->checkSwapStatus();
        return $this->instance->getMinutes();
    }

    public function setTime(int $timestamp) : void
    {
       $this->instance->timestamp = $timestamp;
       $this->save();
    }

    private function newTimer(string $uuid = null) : TimerModel
    {
        $uuid = $uuid ?? uniqid();

       return new TimerModel(
            $uuid,
            time(),
            Type::TIMER,
            Status::WORKING
        );
    }


    private function save() : void
    {
        $this->repo->persist($this->instance);
    }

    public function reset() : void
    {
        $this->instance = $this->newTimer(
            $this->instance->uuid
        );
        $this->save();
    }

    private function checkSwapStatus() : void
    {
        $minutes = $this->instance->getMinutes();

        # Hotfix TODO: investigate why is running is opposite side
        if ($minutes < 0) {
            $this->reset();
        }

        if ($minutes >= self::WORKING_TIME && $this->instance->is(Status::WORKING)) {

            $this->instance->status = Status::BREAK;
            $this->instance->timestamp = time() + Time::getSecondsByMinutes($minutes - self::WORKING_TIME);
            $this->save();
            return;
        }

        if ($minutes >= self::BREAK_TIME && $this->instance->is(Status::BREAK)) {

            $this->instance->status = Status::WORKING;
            $this->instance->timestamp = time() + Time::getSecondsByMinutes($minutes - self::BREAK_TIME);
            $this->save();
        }

    }

    public function getTimestamp() : int
    {
        return $this->instance->timestamp;
    }


}