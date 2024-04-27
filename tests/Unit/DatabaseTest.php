<?php

namespace Timer\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SQLite3;
use SQLite3Result;
use Timer\Database;

#[CoversClass(Database::class)]
class DatabaseTest extends TestCase
{
    const string TEST_DB = TIMER_ROOT . '/test.db';

    public function test_db_connection() : void
    {
        $db = new Database(self::TEST_DB);

        $this->assertInstanceOf(SQLite3::class, $db->connection());
    }

    public function test_table_counter_was_created() : void
    {
        $result = (new Database(self::TEST_DB))->connection()
            ->query('SELECT * FROM counter');

        $this->assertIsObject($result);
        $this->assertInstanceOf(SQLite3Result::class, $result);
    }


}