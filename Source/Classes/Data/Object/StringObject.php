<?php

namespace Neko\Classes\Data;

use Neko\Classes\Data\StringHandler as StringHandler;
use Neko\Classes\Data\ArrayObject as ArrayObject;
use Neko\Classes\Data\BaseObject as BaseObject;
use Neko\Classes\Reflection\Handler as ReflectionHandler;

#[\AllowDynamicProperties]
class StringObject extends BaseObject
{

    protected static $raw_data = '';

    public function __construct($data)
    {
        $this->raw_data = $data;
    }

    public function __toString()
    {
        return $this->raw_data;
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
    public function lastIndexOf(string $needle, int $offset = 0, bool $ignoreCase = true)
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
    public function indexOf($needle, $offset = 0, $ignoreCase = true)
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
    public function trimStart(string|null $characters = " \n\r\t\v\x00")
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
    public function replace(array|string $search, array|string $replace, bool $ignoreCase = true)
    {
        $this->raw_data = $ignoreCase ? str_replace($search, $replace, $this->raw_data) : str_ireplace($search, $replace, $this->raw_data);

        return $this;
    }

    /**
     * Reverse a string
     * 
     * @return StringObject
     */
    public function reverse()
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
    public function trimEnd(string|null $characters = " \n\r\t\v\x00")
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
    public function trim(string $characters = " \n\r\t\v\0")
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
    public function split(string $separator)
    {
        if (empty($separator)) {
            ReflectionHandler::throwEmptyParameterError(self::class, __FUNCTION__, get_defined_vars());
        }

        $array = explode($separator, $this->raw_data);

        return new ArrayObject($array);
    }

    /**
     * Return part of a string
     * 
     * @param int $start
     * @param int|null $length
     * 
     * @return StringObject
     */
    public function substring(int $start, int|null $length = null)
    {
        $this->raw_data = StringHandler::substring($this->raw_data, $start, $length);

        return $this;
    }

    /**
     * Determine whether a variable is considered to be empty
     * 
     * @return bool
     */
    public function isEmpty()
    {
        $boolean = StringHandler::isEmpty($this->raw_data);

        return $boolean;
    }

    /**
     * Finds whether a variable is null
     * 
     * @return StringObject
     */
    public function isNull()
    {
        $this->raw_data = StringHandler::isNull($this->raw_data);

        return $this;
    }

    /**
     * Determine if a string contains a given substring
     * 
     * @param string $needle
     * 
     * @return StringObject
     */
    public function contains(string $needle)
    {
        $this->raw_data = StringHandler::contains($this->raw_data, $needle);

        return $this;
    }

    /**
     * Make a string uppercase
     * 
     * @return StringObject
     */
    public function toUpperCase()
    {
        $this->raw_data = StringHandler::toUpperCase($this->raw_data);

        return $this;
    }

    /**
     * Remove a byte-order mark
     * 
     * @return StringObject
     */
    public function removeByteOrderMark()
    {
        $this->raw_data = StringHandler::removeByteOrderMark($this->raw_data);

        return $this;
    }

    /**
     * Make a string lowercase
     * 
     * @return StringObject
     */
    public function toLowerCase()
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
    public function similar(string $compare)
    {
        $this->raw_data = similar_text($this->raw_data, $compare);

        return $this->raw_data;
    }

    /**
     * Quote meta characters
     * 
     * @return StringObject
     */
    public function quotemeta()
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
    public function metaphone(int|null $max_phonemes = 0)
    {
        $this->raw_data = metaphone($this->raw_data, $max_phonemes);

        return $this;
    }

    /**
     * Calculate Levenshtein distance between two strings
     */
    public function levenshteinDistance($compare, int|null $insertion_cost = 1, int|null $replacement_cost = 1, int|null $deletion_cost = 1)
    {
        $this->raw_data = levenshtein($this->raw_data, $compare, $insertion_cost, $replacement_cost, $deletion_cost);

        return $this->raw_data;
    }

    public function decrement()
    {
        $this->raw_data = str_decrement($this->raw_data);

        return $this;
    }

    public function increment()
    {
        $this->raw_data = str_increment($this->raw_data);

        return $this;
    }

    /**
     * Randomly shuffles a string
     * 
     * @return StringObject
     */
    public function shuffle()
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
    public function repeat(int $times = 0)
    {
        $this->raw_data = str_repeat($this->raw_data, $times);

        return $this;
    }

    /**
     * Get string length
     * 
     * @return int
     */
    public function length()
    {
        $this->raw_data = StringHandler::length($this->raw_data);

        return $this->raw_data;
    }

    /**
     * Remove null bytes
     * 
     * @return StringObject
     */
    public function removeNullByte()
    {
        $this->raw_data = StringHandler::removeNullByte($this->raw_data);

        return $this;
    }

    /**
     * Remove UTF-8 BOM
     * 
     * @return StringObject
     */
    public function removeUtf8Bom()
    {
        $this->raw_data = StringHandler::removeUtf8Bom($this->raw_data);

        return $this;
    }
}
