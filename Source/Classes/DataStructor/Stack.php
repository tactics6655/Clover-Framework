<?php

declare(strict_types=1);

namespace Neko\Classes\DataStructor;

use Neko\Classes\DataStructor\DoubleyLinkedList;

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