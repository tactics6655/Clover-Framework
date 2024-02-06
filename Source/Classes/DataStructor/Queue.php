<?php

declare(strict_types=1);

namespace Neko\Classes\DataStructor;

use Neko\Classes\DataStructor\DoubleyLinkedList;

use SplQueue;

class Queue extends DoubleyLinkedList
{
    public function __construct()
    {
        parent::$stock = new SplQueue();
    }

    public function enqueue($value)
    {
        return parent::$stock->enqueue($value);
    }

    public function dequeue()
    {
        return parent::$stock->dequeue();
    }

}