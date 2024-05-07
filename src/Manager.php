<?php

declare(strict_types=1);

namespace Timer;

use Timer\Repository\TimerRepository;

class Manager
{
    private Database $db;

    public function __construct()
    {
        $this->db = new Database(TIMER_ROOT. '/timer.db');
    }

    public function getCounter() : Counter
    {
        return new Counter($this->db);
    }

    public function getTimer() : Timer
    {
        return new Timer(new TimerRepository($this->db));
    }



}