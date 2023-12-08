<?php

declare(strict_types=1);

namespace Xanax\Classes\OperationSystem;

class Apache 
{
    public static function terminateProcess() :void
    {
        apache_child_terminate();
    }

    public static function getLoadedModulesList() :array
    {
        return apache_get_modules();
    }

    public static function getVersion()
    {
        return apache_get_version();
    }

    public static function setEnvironmentVariable(string $variable, string $value, bool $walkToTop = false)
    {
        return apache_setenv($variable, $value, $walkToTop);
    }

    public static function getEnvironmentVariable(string $variable, bool $walkToTop = false)
    {
        return apache_getenv($variable, $walkToTop);
    }

    public static function lookupUri(string $filename)
    {
        return apache_lookup_uri($filename);
    }

    public static function setRequestNote(string $note_name, ?string $note_value = null)
    {
        apache_note($note_name, $note_value);
    }

    public static function getRequestNote(string $note_name)
    {
        return apache_note($note_name);
    }

    public static function getAllRequestHeaders()
    {
        return apache_request_headers();
    }

    public static function getAllResponseHeaders()
    {
        return apache_response_headers();
    }

    public static function getAllHeaders()
    {
        return getallheaders();
    }

}