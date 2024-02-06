<?php

declare(strict_types=1);

namespace Neko\Classes\DataStructor;

use SplDoublyLinkedList;
use SplQueue;
use SplStack;

class DoubleyLinkedList extends SplDoublyLinkedList
{
    protected static SplDoublyLinkedList|SplQueue|SplStack|null $stock = null;

    #[\ReturnTypeWillChange]
    public function top()
    {
        return self::$stock->top();
    }

    public function pushItem($item)
    {
        self::$stock[] = $item;
    }

}