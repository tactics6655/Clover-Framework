<?php

namespace Clover\Classes\Database\Driver;

use Override;
use PDO;

class ExtendedPdo extends PDO
{
    public const CONNECT_IMMEDIATELY = 'immediate';

    protected array $args = [];
    protected bool $driverSpecific = false;

    public function __construct(
        string $dsn,
        ?string $username = null,
        ?string $password = null,
        ?array $options = null
    ) {
      // if no error mode is specified, use exceptions
        if (! isset($options[PDO::ATTR_ERRMODE])) {
            $options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        }

        // retain the arguments for later
        $this->args = [
            $dsn,
            $username,
            $password,
            $options,
        ];

        if ($options[self::CONNECT_IMMEDIATELY]) {
             $this->establishConnection( $dsn,
             $username,
             $password,
             $options);
        }

        parent::__construct($dsn,
        $username,
        $password,
        $options);
    }

    /*#[\Override]
    public static function connect(string $dsn, ?string $username = null, ?string $password = null, ?array $options = null) 
    {
          $pdo = new self($dsn, $username, $password, $options);
          $pdo->driverSpecific = true;
          return $pdo;
    }*/

    // call this method from query, perform, execute etc
    function establishConnection($dsn, $username, $password, $options): void
    {
       if ($this->pdo) {
            return;
       }

       if ($this->driverSpecific) {
            $this->pdo = PDO::connect($dsn, $username, $password, $options);
        } else {
            $this->pdo = new PDO($dsn, $username, $password, $options);
        }
    }

}