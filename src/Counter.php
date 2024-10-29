<?php

namespace Timer;

use Timer\Enum\Status;
use Timer\Enum\Time;
use Timer\Enum\Type;

class Counter
{
    private string $uuid;

    private Status $status;

    public function __construct(
        private readonly Database $db
    )
    {
        $this->init();
    }

    public function start(int $time = null) : void
    {
        if(!$time) {
            $time = time() - Time::MINUTE_IN_SECONDS;
        }

        $this->db->query('UPDATE counter SET status = :status, timestamp = :timestamp WHERE id = :id', [
            ':status' => Status::RUNNING->value,
            ':timestamp' => $time,
            ':id' => $this->uuid
        ]);

        $this->status = Status::RUNNING;
    }

    public function isRunning() : bool
    {
        $result = $this->db->query('SELECT * FROM counter WHERE id = :id', [
            ':id' => $this->uuid
        ]);

        $data = $result->fetchArray(SQLITE3_ASSOC);

        return $data['status'] === Status::RUNNING->value;
    }

    public function stop(): void
    {
        $this->db->query('UPDATE counter SET status = :status, timestamp = :timestamp WHERE id = :id', [
            ':status' => Status::STOPPED->value,
            ':timestamp' => time(),
            ':id' => $this->uuid
        ]);
    }

    public function reset() : void
    {
        $this->db->query('UPDATE counter SET timestamp = :timestamp WHERE id = :id', [
            ':timestamp' => time() - Time::MINUTE_IN_SECONDS,
            ':id' => $this->uuid
        ]);
    }

    public function increase(int $seconds) : void
    {
        $this->db->query('UPDATE counter SET timestamp = timestamp + :seconds WHERE id = :id', [
            ':seconds' => $seconds,
            ':id' => $this->uuid
        ]);
    }

    private function init() : void
    {
        if($this->counterExist()) {
            $result = $this->db->query('SELECT * FROM counter WHERE type = :type', [
                ':type' => Type::COUNTER->value
            ]);

            $data = $result->fetchArray(SQLITE3_ASSOC);

            $this->uuid = $data['id'];
            $this->status = Status::from($data['status']);

            return;
        }

        $this->uuid = uniqid();
        $this->status = Status::STOPPED;

        $this->db->query(
            'INSERT INTO counter (id, type, timestamp, status) VALUES (:id, :type, :timestamp, :status)', [
                ':id' => $this->uuid,
                ':type' => Type::COUNTER->value,
                ':timestamp' => time() - Time::MINUTE_IN_SECONDS,
                ':status' => Status::STOPPED->value
            ]
        );
    }

    private function counterExist() : bool
    {
        $result = $this->db->query('SELECT * FROM counter WHERE type = :type', [
            ':type' => Type::COUNTER->value
        ]);

        return (bool) $result->fetchArray(SQLITE3_ASSOC);
    }

    public function getTimeInMinutes() : int
    {
        $result = $this->db->query('SELECT * FROM counter WHERE id = :id', [
            ':id' => $this->uuid
        ]);

        $data = $result->fetchArray(SQLITE3_ASSOC);

        return (int) ceil((time() - $data['timestamp']) / Time::MINUTE_IN_SECONDS);
    }

    public function getHourFormat() : string
    {
        $minutes = $this->getTimeInMinutes();

        $hours = floor($minutes / 60);
        $minutes = $minutes % 60;

        return sprintf('%02d:%02d', $hours, $minutes);
    }

    public function getStatus() : Status
    {
        return $this->status;
    }

}