<?php

use function sem_get;

class Semaphore
{

    public static function getId(int $key, int $max_acquire = 1, int $permissions = 0666, bool $auto_release = true)
    {
        if (function_exists("sem_get")) {
            return sem_get($key, $max_acquire, $permissions, $auto_release);
        }

        return false;
    }
}
