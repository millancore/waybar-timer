<?php

namespace Timer;

use Timer\Enum\Status;
use Timer\Enum\Type;

class Timer
{
    private string $uuid;
    private Status $status;

    public function __construct(
        private readonly Database $db
    )
    {
        $this->init();
    }

    public function timerExist() : bool
    {
        $query = $this->db->query(
            'SELECT * FROM counter WHERE type = :type', [
                ':type' => Type::TIMER->value
            ]);

        return $query->fetchArray(SQLITE3_ASSOC) !== false;
    }

    private function init()
    {
        if ($this->timerExist()) {
            $result = $this->db->query(
                'SELECT * FROM counter WHERE type = :type', [
                    ':type' => Type::TIMER->value
                ]
            );

            $data = $result->fetchArray(SQLITE3_ASSOC);

            $this->uuid = $data['id'];
            $this->status = Status::from($data['status']);
        }

        $this->uuid = uniqid();
        $this->status = Status::WORKING;

    }



}