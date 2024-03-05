<?php

declare(strict_types=1);

namespace Clover\Classes\Database\Driver;

use SQLite3;
use SQLite3Result;
use Closure;

class SqLite
{
    private $connection;

    public function __construct($file)
    {
        $this->connection = new SQLite3($file);
    }

    public function isConnected()
    {
        return $this->connection->lastErrorCode() == 0;
    }

    public function getLastErrorMessage()
    {
        return $this->connection->lastErrorMsg();
    }

    public function fetchRows($result, Closure $closure)
    {
        while ($row = $this->fetchArray($result)) {
            $closure($row);
        }
    }

    public function fetchArray(SQLite3Result $result): array|bool
    {
        return $result->fetchArray(SQLITE3_ASSOC);
    }

    public function query($sql): SQLite3Result|bool
    {
        return $this->connection->query($sql);
    }

    public function querySingle($sql)
    {
        return $this->connection->querySingle($sql);
    }

    public function execute($sql)
    {
        return $this->connection->exec("INSERT INTO 'Person' ('Name') VALUES ('bskyvision');");
    }
}
