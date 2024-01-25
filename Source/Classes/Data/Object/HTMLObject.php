<?php

namespace Neko\Classes\Data;

use Neko\Classes\Data\HTMLHandler as HTMLHandler;

#[\AllowDynamicProperties]
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

    public function length()
    {
        return parent::length();
    }

    public function unhtmlSpecialChars()
    {
        return $this->handler->unhtmlSpecialChars($this->raw_data);
    }
}
