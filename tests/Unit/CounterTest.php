<?php

namespace Timer\Tests\Unit;

use Monolog\Test\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Timer\Counter;
use Timer\Database;
use Timer\Enum\Status;
use Timer\Enum\Time;

#[CoversClass(Counter::class)]
class CounterTest extends TestCase
{
    private Database $db;

    protected function setUp(): void
    {
        $this->db = new Database(TIMER_ROOT . '/test.db');
        parent::setUp();
    }


    public function test_can_start_counter() : void
    {
        $time = time();

        $counter = new Counter($this->db);
        $counter->start($time);

        $this->assertTrue($counter->isRunning());
    }

    public function test_can_stop_counter() : void
    {
        $counter = new Counter($this->db);

        $counter->start();
        $counter->stop();

        $this->assertFalse($counter->isRunning());
    }

    public function test_get_time() : void
    {
        $time = time() - Time::getSecondsByMinutes(10);

        $counter = new Counter($this->db);
        $counter->start($time);

        $minutesRunning = $counter->getTimeInMinutes();

        $this->assertIsInt($minutesRunning);
        $this->assertEquals(10, $minutesRunning);

    }

    public function test_get_current_status() : void
    {
        $counter = new Counter($this->db);
        $this->assertEquals(Status::STOPPED, $counter->getStatus());

        $counter->start();
        $this->assertEquals(Status::RUNNING, $counter->getStatus());
    }

}