<?php

namespace Timer\Tests\Unit\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Timer\Database;
use Timer\Enum\Time;
use Timer\Model\TimerModel;
use Timer\Enum\Status;
use Timer\Enum\Type;
use Timer\Repository\TimerRepository;

#[CoversClass(TimerRepository::class)]
class TimerRepositoryTest extends TestCase
{
    private TimerRepository $repository;

    protected function setUp(): void
    {
        $this->repository = new TimerRepository(
            new Database(TIMER_ROOT . '/test.db')
        );

        parent::setUp();
    }


    public function test_get_null_timer_by_not_exist_uuid() : void
    {
        $timer = $this->repository->getTimerById('not-exist-uuid');

        $this->assertNull($timer);
    }

    public function test_persist_timer() : void
    {
        $timer = new TimerModel(
            uniqid(),
            time(),
            Type::TIMER,
            Status::WORKING
        );

        $this->assertInstanceOf(TimerModel::class, $this->repository->persist($timer));

    }

    public function test_get_exist_timer() : void
    {
        $raw = new TimerModel(
            uniqid(),
            time(),
            Type::TIMER,
            Status::WORKING
        );

        $this->repository->persist($raw);
        $timer = $this->repository->getTimerById($raw->uuid);

        $this->assertInstanceOf(TimerModel::class, $timer);
        $this->assertEquals($raw, $timer);
    }



    public function test_update_timer() : void
    {
        $raw = new TimerModel(
            uniqid(),
            time(),
            Type::TIMER,
            Status::WORKING
        );

        $timer = $this->repository->persist($raw);

        $newTime = time() + Time::getSecondsByMinutes(13);

        $timer->timestamp = $newTime;
        $timer->status = Status::STOPPED;

        $updatedTimer = $this->repository->persist($timer);

        $this->assertEquals(Status::STOPPED, $updatedTimer->status);
        $this->assertEquals($newTime, $updatedTimer->timestamp);
    }


}