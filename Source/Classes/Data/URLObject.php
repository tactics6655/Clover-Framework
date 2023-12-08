<?php

namespace Xanax\Classes\Data;

use Xanax\Classes\Data\StringObject as StringObject;

class URLObject extends StringObject
{

    protected static $data;

    public function __construct($data) 
    {
        $this->data = $data;

        parent::__construct($data);
    }

    public function __toString() 
    {
        return $this->data;
    }

    public function getProtocol()
    {
        preg_match("/^[^:]+(?=:\/\/)/i", $this->data, $matches);

        $this->data = is_array($matches) ? $matches[0] : "";

        return new StringObject($this->data);
    }

    public function getDomain()
    {
        preg_match("/(https?(?=:\/\/))?(www\.)?[a-zA-Z0-9\-\_]{0,61}((\.[a-zA-Z]{2,}(\.[a-zA-Z]{2,}))|(\:[0-9]{2,}))/i", $this->data, $matches);

        $this->data = is_array($matches) ? $matches[0] : "";

        return $this;
    }

    public function getQueryString()
    {
        $this->data = parse_url($this->data, PHP_URL_QUERY);

        return $this;
    }

}