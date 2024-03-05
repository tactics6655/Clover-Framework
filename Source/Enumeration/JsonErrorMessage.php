<?php

namespace Clover\Enumeration;

abstract class JsonErrorMessage
{
    const JSON_ERROR_DEPTH = "Maximum stack depth exceeded";

    const JSON_ERROR_STATE_MISMATCH = "Underflow or the modes mismatch";

    const JSON_ERROR_CTRL_CHAR = "Unexpected control character found";

    const JSON_ERROR_SYNTAX = "Syntax error, malformed JSON";

    const JSON_ERROR_UTF8 = "Malformed UTF-8 characters, possibly incorrectly encoded";

    const JSON_ERROR_UNKNOWN = "Unknown error";
}
