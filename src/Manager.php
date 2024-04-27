<?php

declare(strict_types=1);

namespace Timer;

class Manager
{
    private Counter $counter;
    private Database $db;

    public function __construct()
    {
        $this->db = new Database(TIMER_ROOT. '/timer.db');
        $this->counter = new Counter($this->db);
    }

    public function getCounter() : Counter
    {
        return $this->counter;
    }



}