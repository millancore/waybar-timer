<?php

namespace Timer\Repository;

use Timer\Database;
use Timer\Enum\Status;
use Timer\Enum\Type;
use Timer\Model\TimerModel;

class TimerRepository
{
    public function __construct(
        private Database $db
    )
    {
        //
    }

    public function persist(TimerModel $timer): TimerModel
    {
        if($this->exist($timer->uuid)) {
           return $this->update($timer);
        }

        $this->db->query('
        INSERT INTO counter (id, timestamp, type, status) VALUES (:id, :timestamp, :type, :status)', [
            ':id' => $timer->uuid,
            ':timestamp' => $timer->timestamp,
            ':type' => $timer->type->value,
            ':status' => $timer->status->value
        ]);

        return $timer;
    }

    public function exist(string $uuid) : bool
    {
        $result = $this->db->query('SELECT id FROM counter WHERE id = :id AND type = :type', [
            ':id' => $uuid,
            ':type' => Type::TIMER->value
        ]);

        return (bool) $result->fetchArray(SQLITE3_ASSOC);
    }

    public function getTimerById(string $uuid): ?TimerModel
    {
        $result = $this->db->query('SELECT * FROM counter WHERE id = :id', [
            ':id' => $uuid
        ]);

        if (!$data = $result->fetchArray(SQLITE3_ASSOC)) {
            return null;
        }

        return new TimerModel(
            $data['id'],
            $data['timestamp'],
            Type::from($data['type']),
            Status::from($data['status'])
        );

    }

    public function update(TimerModel $timer) : ?TimerModel
    {
        $this->db->query('UPDATE counter SET timestamp = :timestamp, status = :status WHERE id = :id', [
            ':id' => $timer->uuid,
            ':timestamp' => $timer->timestamp,
            ':status' => $timer->status->value
        ]);

        // Refresh
        return $this->getTimerById($timer->uuid);
    }

    public function getCurrentTimer() : ?TimerModel
    {
        $result = $this->db->query('SELECT * FROM counter WHERE type = :type', [
            ':type' => Type::TIMER->value
        ]);

        if (!$data = $result->fetchArray(SQLITE3_ASSOC)) {
            return null;
        }

        return new TimerModel(
            $data['id'],
            $data['timestamp'],
            Type::from($data['type']),
            Status::from($data['status'])
        );
    }

}