<?php

namespace Clover\Classes\Linker;

use Clover\Enumeration\IDE;
use Clover\Enumeration\Protocol;

class IDELink
{

    private ?IDE $ide = null;

    public function __construct(?IDE $ide = null)
    {
        $this->ide = $ide ?? IDE::VISUAL_STUIO_CODE;
    }

    public function generate(string $file, int $line)
    {
        $protocol = Protocol::FILE;
        $format = "%s#L%s";

        switch ($this->ide) {
            case IDE::VISUAL_STUIO_CODE:
                $protocol = Protocol::VISUAL_STUIO_CODE;
                $format = "file/%s:%s";
                break;
            case IDE::EMACS:
                $protocol = Protocol::EMACS;
                $format = "open?url=file://%s&line=%s";
                break;
            case IDE::SUBLIME:
                $protocol = Protocol::SUBLIME;
                $format = "open?url=file://%s&line=%s";
                break;
            case IDE::MAC_VIM:
                $protocol = Protocol::MAC_VIM;
                $format = "open?url=file://%s&line=%s";
                break;
            case IDE::TEXTMATE:
                $protocol = Protocol::TEXTMATE;
                $format = "open?url=file://%s&line=%s";
                break;
            case IDE::PHPSTORM:
                $protocol = Protocol::PHPSTORM;
                $format = "open?file=%s&line=%s";
                break;
            case IDE::ATOM:
                $protocol = Protocol::ATOM;
                $format = "open?file=%s&line=%s";
                break;
        }

        return sprintf("%s%s", $protocol->value, sprintf($format, trim($file, "/"), $line));
    }
}
