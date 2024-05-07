<?php

namespace Timer\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Timer\Database;
use Timer\Enum\Status;
use Timer\Enum\Time;
use Timer\Repository\TimerRepository;
use Timer\Timer;

#[CoversClass(Timer::class)]
class TimerTest extends TestCase
{
    private Timer $timer;

    public function setUp() : void
    {
        $this->timer = $this->newTimer();
        parent::setUp();
    }

    public function test_create_timer_start_to_work() : void
    {
        $this->timer->reset();
        $this->assertEquals(Status::WORKING, $this->timer->getStatus());
    }


    public function test_get_times_minutes_left() : void
    {
        $this->timer->reset();

        $currentTime = time();
        $tenMinutesAsSeconds = Time::getSecondsByMinutes(10);
        $passTime = $currentTime - $tenMinutesAsSeconds;

        $this->timer->setTime($passTime);

        $minutes = $this->timer->getTimeMinutesSoFar();

        $this->assertIsInt($minutes);
        $this->assertEquals(10, $minutes);
    }

    public function test_swap_timer_when_working_end() : void
    {
        $this->timer->reset();

        $this->timer->setTime(time() - Time::getSecondsByMinutes(55));

        $minutes = $this->timer->getTimeMinutesSoFar();

        $this->assertEquals(Status::BREAK, $this->timer->getStatus());
        $this->assertEquals(-5, $minutes);

        $this->timer->setTime($this->timer->getTimestamp() - Time::getSecondsByMinutes(16));

        $minutes = $this->timer->getTimeMinutesSoFar();

        $this->assertEquals(Status::WORKING, $this->timer->getStatus());
        $this->assertEquals(-1, $minutes);
    }

    private function newTimer(): Timer
    {
        return new Timer(new TimerRepository(new Database(TIMER_ROOT . '/test.db')));
    }


}