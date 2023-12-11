<?php

namespace Xanax\Classes\Data;

use Xanax\Classes\Data\HTMLHandler as HTMLHandler;

class HTMLObject extends StringObject
{

    protected static $raw_data;
    private $handler;

    public function __construct($data) 
    {
        parent::__construct($data);

        $this->raw_data = $data;

        $this->handler = new HTMLHandler();
    }

    public function Length()
    {
        return parent::Length();
    }

    public function unhtmlSpecialChars()
    {
        return $this->handler->unhtmlSpecialChars($this->raw_data);
    }

}