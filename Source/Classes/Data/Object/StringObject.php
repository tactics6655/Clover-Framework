<?php

namespace Clover\Classes\Data;

use Clover\Classes\Data\StringHandler as StringHandler;
use Clover\Classes\Data\ArrayObject as ArrayObject;
use Clover\Classes\Data\BaseObject as BaseObject;
use Clover\Classes\Reflection\Handler as ReflectionHandler;
use Clover\Classes\Data\Multibyte as Multibyte;
use Clover\Enumeration\Encoding;

use function mb_strcut;

#[\AllowDynamicProperties]
class StringObject extends BaseObject
{

    protected $rawData = '';

    public function __construct($data)
    {
        $this->rawData = $data;
    }

    public function __toString(): string
    {
        return $this->rawData;
    }

    public function getHexDecimal(): self
    {
        preg_match('/^0x[0-9a-f_]++$/i', $this->rawData, $matches);

        if (isset($matches)) {
            $this->rawData = $matches[0];
        }

        return $this;
    }

    public function getEncoding(): self
    {
        $this->rawData = Multibyte::detectCharacterEncoding($this->rawData);

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
        $this->rawData = $ignoreCase ? strrpos($this->rawData, $needle, $offset) : strripos($this->rawData, $needle, $offset);

        return $this->rawData;
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
        $this->rawData = $ignoreCase ? strpos($this->rawData, $needle, $offset) : stripos($this->rawData, $needle, $offset);

        return $this->rawData;
    }

    /**
     * Strip whitespace (or other characters) from the beginning of a string.
     * 
     * @param string|null $characters
     * 
     * @return StringObject
     */
    public function trimStart(string|null $characters = " \n\r\t\v\x00"): self
    {
        $this->rawData = ltrim($this->rawData, $characters);

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
    public function replace(array|string $search, array|string $replace, bool $ignoreCase = true): self
    {
        $this->rawData = $ignoreCase ? str_replace($search, $replace, $this->rawData) : str_ireplace($search, $replace, $this->rawData);

        return $this;
    }

    /**
     * Reverse a string
     * 
     * @return StringObject
     */
    public function reverse(): self
    {
        $this->rawData = strrev($this->rawData);

        return $this;
    }

    /**
     * Strip whitespace (or other characters) from the end of a string
     * 
     * @param string|null $characters
     * 
     * @return StringObject
     */
    public function trimEnd(string|null $characters = " \n\r\t\v\x00"): self
    {
        $this->rawData = rtrim($this->rawData, $characters);

        return $this;
    }

    /**
     * Strip whitespace (or other characters) from the beginning and end of a string
     * 
     * @param string|null $characters
     * 
     * @return StringObject
     */
    public function trim(string $characters = " \n\r\t\v\0"): self
    {
        $this->rawData = trim($this->rawData, $characters);

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

        $array = explode($separator, $this->rawData);

        return new ArrayObject($array);
    }

    public function startsWith(string $string): int
    {
        return strpos($this->rawData, $string) === 0;
    }

    public function endsWith(string $string): int
    {
        return strpos($this->rawData, $string) === (strlen($this->rawData) - strlen($string));
    }

    /**
     * Return part of a string
     * 
     * @param int $start
     * @param int|null $length
     * 
     * @return StringObject
     */
    public function substring(int $start, int|null $length = null): self
    {
        if (extension_loaded('mbstring')) {
            $this->rawData = mb_strcut($this->rawData, $start, $length);
        } else {
            $this->rawData = StringHandler::substring($this->rawData, $start, $length);
        }

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
        return substr_count(haystack: $this->rawData, needle: $needle, offset: $offset, length: $length);
    }

    /**
     * Binary safe comparison of two strings from an offset, up to length characters
     */
    public function substringCompare(string $needle, int $offset): int
    {
        return substr_compare($this->rawData, $needle, $offset);
    }

    /**
     * Determine whether a variable is considered to be empty
     * 
     * @return bool
     */
    public function isEmpty(): bool
    {
        $boolean = StringHandler::isEmpty($this->rawData);

        return $boolean;
    }

    /**
     * Finds whether a variable is null
     * 
     * @return bool
     */
    public function isNull(): bool
    {
        return StringHandler::isNull($this->rawData);
    }

    /**
     * Determine if a string contains a given substring
     * 
     * @param string $needle
     * 
     * @return StringObject
     */
    public function contains(string $needle): self
    {
        $this->rawData = StringHandler::contains($this->rawData, $needle);

        return $this;
    }

    /**
     * Camelize the string
     * 
     * @return StringObject
     */
    public function camelize(): self
    {
        $this->rawData = StringHandler::camelize($this->rawData);

        return $this;
    }

    public function print(): void
    {
        echo $this->rawData;
    }

    /**
     * Replaces all both side strings
     * 
     * @param string $replace
     * 
     * @return StringObject
     */
    public function replaceBoth($string): self
    {
        $length = strlen($string);

        $this->rawData = preg_replace("/^\w{{$length}}(.*)\w{{$length}}/i", "$string\$1$string", $this->rawData);

        return $this;
    }

    public function replaceCenter($string): self
    {
        $length = ceil(strlen($this->rawData) / 2) - ceil(strlen($string) / 2);
        $tail = strlen($this->rawData) - (($length * 2) + strlen($string));
        $append = $tail > 0 ? str_repeat($string[0], $tail) : "";
        $string .= $append;

        $this->rawData = preg_replace("/^(\w{{$length}}).*(\w{{$length}})/i", "\$1$string\$2", $this->rawData);

        return $this;
    }

    public function appendBoth($string): self
    {
        $lenth = strlen($string);
        $this->rawData = $string . $this->rawData . $string;

        return $this;
    }

    public function appendWord($string): self
    {
        $lenth = strlen($string);
        $this->rawData = preg_replace("/(?!\s)(?!\s\b.{0,1}\b)/i", $string, $this->rawData);

        return $this;
    }

    public function appendInner($string): self
    {
        $lenth = strlen($string);
        $this->rawData = preg_replace("/(?:\s)(\b.{0,1}\b)(?!\s)/i", "{$string}\$1", $this->rawData);

        return $this;
    }

    /**
     * Make a string's first character uppercase
     * 
     * @return StringObject
     */
    public function capitalizeFirstLetter(): self
    {
        $this->rawData = ucfirst($this->rawData);

        return $this;
    }

    /**
     * Tokenize string
     * 
     * @return StringObject | bool
     */
    public function tokenize(): self | bool
    {
        $this->rawData = strtok($this->rawData);

        if (!$this->rawData) {
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
    public function match(string $string, &$matches): self
    {
        $this->rawData = preg_match($string, $this->rawData, $matches);

        return $this;
    }

    /**
     * Replaces all even position strings
     * 
     * @param string $replace
     * 
     * @return StringObject
     */
    public function replaceEven(string $replace): self
    {
        $this->rawData = preg_replace('/(.)./', "\$1{$replace}", $this->rawData);

        return $this;
    }

    /**
     * Replaces all odd position strings
     * 
     * @param string $replace
     * 
     * @return StringObject
     */
    public function replaceOdd(string $replace): self
    {
        $this->rawData = preg_replace('/.(.)/', "{$replace}\$1", $this->rawData);

        return $this;
    }

    /**
     * Cut the string
     * 
     * @param int $length
     * 
     * @return StringObject
     */
    public function cut(int $length): self
    {
        $this->rawData = substr($this->rawData, 0, $length);

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
    public function limit(int $limit, string $trimMarker = ''): self
    {
        $this->rawData = mb_strimwidth($this->rawData, 0, $limit, $trimMarker);

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
        return strcoll($this->rawData, $string);
    }

    /**
     * Make a string's first character lowercase
     * 
     * @return StringObject
     */
    public function lowercaseFirstLetter(): self
    {
        $this->rawData = lcfirst($this->rawData);

        return $this;
    }

    /**
     * Make a string uppercase
     * 
     * @return StringObject
     */
    public function toUpperCase(): self
    {
        $this->rawData = StringHandler::toUpperCase($this->rawData);

        return $this;
    }

    /**
     * Remove a byte-order mark
     * 
     * @return StringObject
     */
    public function removeByteOrderMark(): self
    {
        $this->rawData = StringHandler::removeByteOrderMark($this->rawData);

        return $this;
    }

    /**
     * Make a string lowercase
     * 
     * @return StringObject
     */
    public function toLowerCase(): self
    {
        $this->rawData = StringHandler::toLowerCase($this->rawData);

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
        return similar_text($this->rawData, $compare);
    }

    /**
     * Quote meta characters
     * 
     * @return StringObject
     */
    public function quotemeta(): self
    {
        $this->rawData = quotemeta($this->rawData);

        return $this;
    }

    /**
     * Calculates the metaphone key of string.
     * 
     * @param int|null $max_phonemes
     * 
     * @return StringObject
     */
    public function metaphone(int|null $max_phonemes = 0): self
    {
        $this->rawData = metaphone($this->rawData, $max_phonemes);

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
        return levenshtein($this->rawData, $compare, $insertion_cost, $replacement_cost, $deletion_cost);
    }

    public function decrement(): bool|self
    {
        if (PHP_VERSION_ID < 80300) {
            return false;
        }

        $this->rawData = str_decrement($this->rawData);

        return $this;
    }

    public function increment(): bool|self
    {
        if (PHP_VERSION_ID < 80300) {
            return false;
        }

        $this->rawData = str_increment($this->rawData);

        return $this;
    }

    /**
     * Randomly shuffles a string
     * 
     * @return StringObject
     */
    public function shuffle(): self
    {
        $this->rawData = str_shuffle($this->rawData);

        return $this;
    }

    /**
     * Repeat a string
     * 
     * @param int $times
     * 
     * @return StringObject
     */
    public function repeat(int $times = 0): self
    {
        $this->rawData = str_repeat($this->rawData, $times);

        return $this;
    }

    /**
     * Get string length
     * 
     * @return int
     */
    public function length(): int
    {
        return StringHandler::length($this->rawData);
    }

    /**
     * Remove null bytes
     * 
     * @return StringObject
     */
    public function removeNullByte(): self
    {
        $this->rawData = StringHandler::removeNullByte($this->rawData);

        return $this;
    }

    /**
     * Concatenates the string
     * 
     * @param string $string
     * 
     * @return StringObject
     */
    public function concat(string $string): self
    {
        $this->rawData = sprintf("%s%s", $this->rawData, $string);

        return $this;
    }

    /**
     * Remove UTF-8 BOM
     * 
     * @return StringObject
     */
    public function removeUtf8Bom(): self
    {
        $this->rawData = StringHandler::removeUtf8Bom($this->rawData);

        return $this;
    }
}
