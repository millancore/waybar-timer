<?php

namespace Timer;

use SQLite3;
use SQLite3Result;

class Database
{
    private string $dbFile;

    private SQLite3 $connection;

    public function __construct(string $dbFile)
    {
        $this->dbFile = $dbFile;
        $this->init();
    }

    private function init(): void
    {
        $this->connection = new SQLite3($this->dbFile, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
        $this->connection->enableExceptions(true);

        $this->connection->exec(
            'CREATE TABLE IF NOT EXISTS counter (
                id TEXT PRIMARY KEY,
                type INTEGER,
                timestamp INTEGER,
                status INTEGER
            )'
        );
    }

    public function query( string $query, array $bindings = [] ) : false|SQLite3Result
    {
        $stmt = $this->connection->prepare($query);
        foreach ($bindings as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        return $stmt->execute();
    }

    public function connection() : SQLite3
    {
        return $this->connection;
    }

}