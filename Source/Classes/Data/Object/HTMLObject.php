<?php

namespace Clover\Classes\Data;

use Clover\Classes\Data\HTMLHandler as HTMLHandler;

#[\AllowDynamicProperties]
class HTMLObject extends StringObject
{

    protected $raw_data;
    private $handler;

    public function __construct($data)
    {
        parent::__construct($data);

        $this->raw_data = $data;

        $this->handler = new HTMLHandler();
    }

    public function length(): int
    {
        return parent::length();
    }

    public function unhtmlSpecialChars()
    {
        return $this->handler->unhtmlSpecialChars($this->raw_data);
    }
}
