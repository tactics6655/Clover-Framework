<?php

namespace Clover\Classes\Data;

use Clover\Classes\Data\StringObject as StringObject;

#[\AllowDynamicProperties]
class URLObject extends StringObject
{

    protected $rawData;

    public function __construct($data)
    {
        $this->rawData = $data;

        parent::__construct($data);
    }

    public function __toString()
    {
        return $this->rawData;
    }

    public function getProtocol()
    {
        preg_match("/^[^:]+(?=:\/\/)/i", $this->rawData, $matches);

        $this->rawData = is_array($matches) ? $matches[0] : "";

        return new StringObject($this->rawData);
    }

    public function getDomain()
    {
        preg_match("/(https?(?=:\/\/))?(www\.)?[a-zA-Z0-9\-\_]{0,61}((\.[a-zA-Z]{2,}(\.[a-zA-Z]{2,}))|(\:[0-9]{2,}))/i", $this->rawData, $matches);

        $this->rawData = empty($matches) ? "" : (is_array($matches) ? $matches[0] : "");

        return $this;
    }

    public function getQueryString()
    {
        $this->rawData = parse_url($this->rawData, PHP_URL_QUERY);

        return $this;
    }
}
