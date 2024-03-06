<?php

namespace Clover\Classes\Data;

class Multibyte
{

    /**
     * Get the internal character encoding
     */
    public static function getInternalCharacterEncoding(string|null $encoding = null): bool|string
    {
        return mb_internal_encoding($encoding);
    }

    /**
     * Convert character encoding
     */
    public static function convertCharacterEncoding(array|string $string, string $to_encoding, null|array|string $from_encoding = null)
    {
        return mb_convert_encoding($string, $to_encoding, $from_encoding);
    }

    public static function encodingToDetectEncoding(string $string, $encoding = 'UTF-8')
    {
        return mb_convert_encoding($string, mb_detect_encoding($string), $encoding);
    }

    /**
     * Detect character encoding
     */
    public static function detectCharacterEncoding(string $string, array|string|null $encodings = null, bool|null $strict = false)
    {
        return mb_detect_encoding($string, $encodings, $strict);
    }

    /**
     * Get string length
     */
    public static function length(string $string, string|null $encoding = null): int
    {
        return mb_strlen($string, $encoding);
    }

    /**
     * Get part of string
     */
    public static function substring(string $string, int $start, int|null $length = null, string|null $encoding = null): string
    {
        return mb_substr($string, $start, $length, $encoding);
    }

    /**
     * Get Unicode code point of character
     */
    public static function getUnicodeCodePointOfCharacter(string $string, string|null $encoding = null)
    {
        return mb_ord($string, $encoding);
    }

    public static function stringPadding($string = "", $length = 0, $padString = " ", $type = STR_PAD_RIGHT, string|null $encoding = null)
    {
        if (!function_exists("mb_str_pad")) {
            return false;
        }

        return mb_str_pad($string, $length, $padString, $type, $encoding);
    }

    /**
     * Find position of first occurrence of string in a string
     */
    public static function findsPositionOfTheFirstOccurrence(string $haystack, string $needle, int|null $offset = 0, string|null $encoding = null)
    {
        if (function_exists('mb_strpos')) {
            return false;
        }

        return mb_strpos($haystack, $needle, $offset, $encoding);
    }

    /**
     * Make a string uppercase
     */
    public static function toUpperCase(string $string, string|null $encoding = null)
    {
        return mb_strtoupper($string, $encoding);
    }
}
