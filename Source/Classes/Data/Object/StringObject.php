<?php

namespace Neko\Classes\Data;

use Neko\Classes\Data\StringHandler as StringHandler;
use Neko\Classes\Data\ArrayObject as ArrayObject;
use Neko\Classes\Data\BaseObject as BaseObject;
use Neko\Classes\Reflection\Handler as ReflectionHandler;
use Neko\Classes\Data\Multibyte as Multibyte;
use Neko\Enumeration\Encoding;

#[\AllowDynamicProperties]
class StringObject extends BaseObject
{

    protected $raw_data = '';

    public function __construct($data)
    {
        $this->raw_data = $data;
    }

    public function __toString()
    {
        return $this->raw_data;
    }

    public function getEncoding(): StringObject
    {
        $this->raw_data = Multibyte::detectCharacterEncoding($this->raw_data);

        return $this;
    }

    /**
     * Find the position of the last occurrence of a substring in a string
     * 
     * @param string $needle
     * @param int $offset
     * @param bool $ignoreCase
     * 
     * @return int|bool
     */
    public function lastIndexOf(string $needle, int $offset = 0, bool $ignoreCase = true): int|bool
    {
        $this->raw_data = $ignoreCase ? strrpos($this->raw_data, $needle, $offset) : strripos($this->raw_data, $needle, $offset);

        return $this->raw_data;
    }

    /**
     * Find the position of the first occurrence of a substring in a string
     * 
     * @param string $needle
     * @param int $offset
     * @param bool $ignoreCase
     * 
     * @return bool|int
     */
    public function indexOf($needle, $offset = 0, $ignoreCase = true): bool|int
    {
        $this->raw_data = $ignoreCase ? strpos($this->raw_data, $needle, $offset) : stripos($this->raw_data, $needle, $offset);

        return $this->raw_data;
    }

    /**
     * Strip whitespace (or other characters) from the beginning of a string.
     * 
     * @param string|null $characters
     * 
     * @return StringObject
     */
    public function trimStart(string|null $characters = " \n\r\t\v\x00"): StringObject
    {
        $this->raw_data = ltrim($this->raw_data, $characters);

        return $this;
    }

    /**
     * Replace all occurrences of the search string with the replacement string
     * 
     * @param array|string $search
     * @param array|string $replace
     * @param bool $ignoreCase
     * 
     * @return StringObject
     */
    public function replace(array|string $search, array|string $replace, bool $ignoreCase = true): StringObject
    {
        $this->raw_data = $ignoreCase ? str_replace($search, $replace, $this->raw_data) : str_ireplace($search, $replace, $this->raw_data);

        return $this;
    }

    /**
     * Reverse a string
     * 
     * @return StringObject
     */
    public function reverse(): StringObject
    {
        $this->raw_data = strrev($this->raw_data);

        return $this;
    }

    /**
     * Strip whitespace (or other characters) from the end of a string
     * 
     * @param string|null $characters
     * 
     * @return StringObject
     */
    public function trimEnd(string|null $characters = " \n\r\t\v\x00"): StringObject
    {
        $this->raw_data = rtrim($this->raw_data, $characters);

        return $this;
    }

    /**
     * Strip whitespace (or other characters) from the beginning and end of a string
     * 
     * @param string|null $characters
     * 
     * @return StringObject
     */
    public function trim(string $characters = " \n\r\t\v\0"): StringObject
    {
        $this->raw_data = trim($this->raw_data, $characters);

        return $this;
    }

    /**
     * Split a string by a string
     * 
     * @param string $separator
     * 
     * @return ArrayObject
     */
    public function split(string $separator): ArrayObject
    {
        if (empty($separator)) {
            ReflectionHandler::throwEmptyParameterError(self::class, __FUNCTION__, get_defined_vars());
        }

        $array = explode($separator, $this->raw_data);

        return new ArrayObject($array);
    }

    public function startsWith(string $string): int
    {
        return strpos($this->raw_data, $string) === 0;
    }

    public function endsWith(string $string): int
    {
        return strpos($this->raw_data, $string) === (strlen($this->raw_data) - strlen($string));
    }

    /**
     * Return part of a string
     * 
     * @param int $start
     * @param int|null $length
     * 
     * @return StringObject
     */
    public function substring(int $start, int|null $length = null): StringObject
    {
        $this->raw_data = StringHandler::substring($this->raw_data, $start, $length);

        return $this;
    }

    /**
     * Count the number of substring occurrences
     * 
     * @param string $needle
     * @param int $offset
     * 
     * @return int
     */
    public function substringCount(string $needle, int $offset = 0, ?int $length = null): int
    {
        return substr_count(haystack: $this->raw_data, needle: $needle, offset: $offset, length: $length);
    }

    /**
     * Binary safe comparison of two strings from an offset, up to length characters
     */
    public function substringCompare(string $needle, int $offset)
    {
        return substr_compare($this->raw_data, $needle, $offset);
    }

    /**
     * Determine whether a variable is considered to be empty
     * 
     * @return bool
     */
    public function isEmpty(): bool
    {
        $boolean = StringHandler::isEmpty($this->raw_data);

        return $boolean;
    }

    /**
     * Finds whether a variable is null
     * 
     * @return bool
     */
    public function isNull(): bool
    {
        return StringHandler::isNull($this->raw_data);
    }

    /**
     * Determine if a string contains a given substring
     * 
     * @param string $needle
     * 
     * @return StringObject
     */
    public function contains(string $needle): StringObject
    {
        $this->raw_data = StringHandler::contains($this->raw_data, $needle);

        return $this;
    }

    /**
     * Camelize the string
     * 
     * @return StringObject
     */
    public function camelize(): StringObject
    {
        $this->raw_data = StringHandler::camelize($this->raw_data);

        return $this;
    }

    /**
     * Replaces all both side strings
     * 
     * @param string $replace
     * 
     * @return StringObject
     */
    public function replaceBoth($string): StringObject
    {
        $length = strlen($string);

        $this->raw_data = preg_replace("/^\w{{$length}}(.*)\w{{$length}}/i", "$string\$1$string", $this->raw_data);

        return $this;
    }

    public function replaceCenter($string): StringObject
    {
        $length = ceil(strlen($this->raw_data) / 2) - ceil(strlen($string) / 2);
        $tail = strlen($this->raw_data) - (($length * 2) + strlen($string));
        $append = $tail > 0 ? str_repeat($string[0], $tail) : "";
        $string .= $append;

        $this->raw_data = preg_replace("/^(\w{{$length}}).*(\w{{$length}})/i", "\$1$string\$2", $this->raw_data);

        return $this;
    }

    public function appendBoth($string): StringObject
    {
        $lenth = strlen($string);
        $this->raw_data = $string . $this->raw_data . $string;

        return $this;
    }

    public function appendWord($string): StringObject
    {
        $lenth = strlen($string);
        $this->raw_data = preg_replace("/(?!\s)(?!\s\b.{0,1}\b)/i", $string, $this->raw_data);

        return $this;
    }

    public function appendInner($string): StringObject
    {
        $lenth = strlen($string);
        $this->raw_data = preg_replace("/(?:\s)(\b.{0,1}\b)(?!\s)/i", "{$string}\$1", $this->raw_data);

        return $this;
    }

    /**
     * Make a string's first character uppercase
     * 
     * @return StringObject
     */
    public function capitalizeFirstLetter(): StringObject
    {
        $this->raw_data = ucfirst($this->raw_data);

        return $this;
    }

    /**
     * Tokenize string
     * 
     * @return StringObject | bool
     */
    public function tokenize(): StringObject | bool
    {
        $this->raw_data = strtok($this->raw_data);

        if (!$this->raw_data) {
            return false;
        }

        return $this;
    }

    /**
     * Perform a regular expression match
     * 
     * @param string $string
     * @param &$matches
     * 
     * @return StringObject
     */
    public function match(string $string, &$matches): StringObject
    {
        $this->raw_data = preg_match($string, $this->raw_data, $matches);

        return $this;
    }

    /**
     * Replaces all even position strings
     * 
     * @param string $replace
     * 
     * @return StringObject
     */
    public function replaceEven(string $replace): StringObject
    {
        $this->raw_data = preg_replace('/(.)./', "\$1{$replace}", $this->raw_data);

        return $this;
    }

    /**
     * Replaces all odd position strings
     * 
     * @param string $replace
     * 
     * @return StringObject
     */
    public function replaceOdd(string $replace): StringObject
    {
        $this->raw_data = preg_replace('/.(.)/', "{$replace}\$1", $this->raw_data);

        return $this;
    }

    /**
     * Cut the string
     * 
     * @param int $length
     * 
     * @return StringObject
     */
    public function cut(int $length): StringObject
    {
        $this->raw_data = substr($this->raw_data, 0, $length);

        return $this;
    }

    /**
     * Get truncated string with specified width
     * 
     * @param int $limit
     * @param string $trimMarker = ''
     * 
     * @return StringObject
     */
    public function limit(int $limit, string $trimMarker = ''): StringObject
    {
        $this->raw_data = mb_strimwidth($this->raw_data, 0, $limit, $trimMarker);

        return $this;
    }

    /**
     * Locale based string comparison
     * 
     * @param string $string
     * 
     * @return int
     */
    public function localeBasedComparison(string $string): int
    {
        return strcoll($this->raw_data, $string);
    }

    /**
     * Make a string's first character lowercase
     * 
     * @return StringObject
     */
    public function lowercaseFirstLetter(): StringObject
    {
        $this->raw_data = lcfirst($this->raw_data);

        return $this;
    }

    /**
     * Make a string uppercase
     * 
     * @return StringObject
     */
    public function toUpperCase(): StringObject
    {
        $this->raw_data = StringHandler::toUpperCase($this->raw_data);

        return $this;
    }

    /**
     * Remove a byte-order mark
     * 
     * @return StringObject
     */
    public function removeByteOrderMark(): StringObject
    {
        $this->raw_data = StringHandler::removeByteOrderMark($this->raw_data);

        return $this;
    }

    /**
     * Make a string lowercase
     * 
     * @return StringObject
     */
    public function toLowerCase(): StringObject
    {
        $this->raw_data = StringHandler::toLowerCase($this->raw_data);

        return $this;
    }

    /**
     * Calculate the similarity between two strings
     * 
     * @param string $compare
     * 
     * @return int
     */
    public function similar(string $compare): int
    {
        return similar_text($this->raw_data, $compare);
    }

    /**
     * Quote meta characters
     * 
     * @return StringObject
     */
    public function quotemeta(): StringObject
    {
        $this->raw_data = quotemeta($this->raw_data);

        return $this;
    }

    /**
     * Calculates the metaphone key of string.
     * 
     * @param int|null $max_phonemes
     * 
     * @return StringObject
     */
    public function metaphone(int|null $max_phonemes = 0): StringObject
    {
        $this->raw_data = metaphone($this->raw_data, $max_phonemes);

        return $this;
    }

    /**
     * Calculate Levenshtein distance between two strings
     * 
     * @param string $compare
     * @param int|null $insertion_cost = 1
     * @param int|null $replacement_cost = 1
     * @param int|null $deletion_cost = 1
     * 
     * @return int
     */
    public function levenshteinDistance(string $compare, int|null $insertion_cost = 1, int|null $replacement_cost = 1, int|null $deletion_cost = 1): int
    {
        return levenshtein($this->raw_data, $compare, $insertion_cost, $replacement_cost, $deletion_cost);
    }

    public function decrement()
    {
        if (PHP_VERSION_ID < 80300) {
            return false;
        }

        $this->raw_data = str_decrement($this->raw_data);

        return $this;
    }

    public function increment()
    {
        if (PHP_VERSION_ID < 80300) {
            return false;
        }

        $this->raw_data = str_increment($this->raw_data);

        return $this;
    }

    /**
     * Randomly shuffles a string
     * 
     * @return StringObject
     */
    public function shuffle(): StringObject
    {
        $this->raw_data = str_shuffle($this->raw_data);

        return $this;
    }

    /**
     * Repeat a string
     * 
     * @param int $times
     * 
     * @return StringObject
     */
    public function repeat(int $times = 0): StringObject
    {
        $this->raw_data = str_repeat($this->raw_data, $times);

        return $this;
    }

    /**
     * Get string length
     * 
     * @return int
     */
    public function length(): int
    {
        return StringHandler::length($this->raw_data);
    }

    /**
     * Remove null bytes
     * 
     * @return StringObject
     */
    public function removeNullByte(): StringObject
    {
        $this->raw_data = StringHandler::removeNullByte($this->raw_data);

        return $this;
    }

    /**
     * Concatenates the string
     * 
     * @param string $string
     * 
     * @return StringObject
     */
    public function concat(string $string): StringObject
    {
        $this->raw_data = sprintf("%s%s", $this->raw_data, $string);

        return $this;
    }

    /**
     * Remove UTF-8 BOM
     * 
     * @return StringObject
     */
    public function removeUtf8Bom(): StringObject
    {
        $this->raw_data = StringHandler::removeUtf8Bom($this->raw_data);

        return $this;
    }
}
