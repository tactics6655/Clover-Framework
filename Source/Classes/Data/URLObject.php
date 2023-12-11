<?php

namespace Xanax\Classes\Data;

use Xanax\Classes\Data\StringObject as StringObject;

#[\AllowDynamicProperties]
class URLObject extends StringObject
{

    protected static $raw_data;

    public function __construct($data) 
    {
        $this->raw_data = $data;

        parent::__construct($data);
    }

    public function __toString() 
    {
        return $this->raw_data;
    }

    public function getProtocol()
    {
        preg_match("/^[^:]+(?=:\/\/)/i", $this->raw_data, $matches);

        $this->raw_data = is_array($matches) ? $matches[0] : "";

        return new StringObject($this->raw_data);
    }

    public function getDomain()
    {
        preg_match("/(https?(?=:\/\/))?(www\.)?[a-zA-Z0-9\-\_]{0,61}((\.[a-zA-Z]{2,}(\.[a-zA-Z]{2,}))|(\:[0-9]{2,}))/i", $this->raw_data, $matches);

        $this->raw_data = is_array($matches) ? $matches[0] : "";

        return $this;
    }

    public function getQueryString()
    {
        $this->raw_data = parse_url($this->raw_data, PHP_URL_QUERY);

        return $this;
    }

}