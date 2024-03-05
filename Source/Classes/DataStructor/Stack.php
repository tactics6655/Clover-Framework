<?php

declare(strict_types=1);

namespace Clover\Classes\DataStructor;

use Clover\Classes\DataStructor\DoubleyLinkedList;

use SplStack;
use SplDoublyLinkedList;

class Stack extends DoubleyLinkedList
{
    public function __construct()
    {
        parent::$stock = new SplStack();
        parent::$stock->setIteratorMode(SplDoublyLinkedList::IT_MODE_LIFO | SplDoublyLinkedList::IT_MODE_KEEP);
    }
}
