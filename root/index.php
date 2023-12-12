<?php

use Xanax\Framework\Component\Runtime;
use Xanax\Classes\QueryBuilder;

include("./../vendor/autoload.php");

//$runtime = new Runtime();


$queryBuilder = new QueryBuilder();
$query = $queryBuilder->select()
    ->columns()
        ->addSelect()->columns('test')->close()
    ->close();