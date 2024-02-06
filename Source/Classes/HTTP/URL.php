<?php

declare(strict_types=1);

namespace Neko\Classes\HTTP;

class URL
{
    /**
     * Generate URL-encoded query string
     */
    public static function generateEncodedQueryString(object|array $data, string $numeric_prefix = "", ?string $arg_separator = null, int $encoding_type = PHP_QUERY_RFC1738)
    {
        return http_build_query($data, $numeric_prefix, $arg_separator, $encoding_type);
    }
}
