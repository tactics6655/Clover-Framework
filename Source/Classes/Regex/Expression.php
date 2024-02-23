<?php

namespace Neko\Classes\Regex;

use Neko\Traits\Regex\Error;

class Expression
{

	// http://vs-shop.cloudsite.ir/manual/kr/language.variables.basics.php
	public function validPHPVariableName()
	{
		return "[a-zA-Z_\x7f-\xff]";
	}

	public function negativeSet($expression)
	{
		return "[^{$expression}]";
	}

	public function url()
	{
		return "({$this->positiveLookbehind()}(([\<a href])))?(?2)?({$this->atomic()})(?1)({$this->positiveLookahead()}[http|https]).{4,5}[A-Za-z]\:\/\/\b[0-9a-zA-Z\?\=.\/\_\-]{1,}\b";
	}

	public function repetition($expression, $repeat)
	{
		return "\\b{$expression}\{{$repeat}\}\b";
	}

	public function setComment($content)
	{
		return "(?#{$content})";
	}

	public function recursion()
	{
		return "(?R)";
	}

	// Subroutine

	public function namedCapturing($name, $expression)
	{
		return "(?P<{$name}{$expression}>";
	}

	public function namedSubroutine($name)
	{
		return "(?P>\${$name})";
	}

	public function numericSubroutine($number)
	{
		return "(?\${$number})"; // isEqual \g'${$number}'
	}

	public function numericPrecedingSubroutine($number)
	{
		return "(?-\${$number})";
	}

	public function numericNextSubroutine($number)
	{
		return "(?+\${$number})";
	}

	public function backreferenceNumericSubroutine($expression, $number)
	{
		return "({$expression})\g<{$number}>";
	}

	// Condition

	public function condition($expression, $then, $else)
	{
		return "(?({$this->positiveLookahead()}{$expression}){$then}|{$else})";
	}

	public function namedConditionGroupingWhenValid($name, $expression, $condition, $then, $else)
	{
		return "(?<{$name}>{$expression})?{$condition}(?({$name}){$then}|{$else})";
	}

	public function conditionGrouping($expression, $condition, $then, $else)
	{
		return "({$expression})?{$condition}(?(1){$then}|{$else})";
	}

	// Mode

	/**
	 * Ignore whitespace and allow comments
	 */
	public function turnOnFreeSpacingMode()
	{
		return "(?x)";
	}

	public function caseInsensitive()
	{
		return "(?i)";
	}

	public function caseSensitive()
	{
		return "(?c)";
	}

	// Only supported by Tcl
	public function turnOffFreeSpacingMode()
	{
		return "(?t)";
	}

	/**
	 * Treats the dot as matching any characters, including newline
	 */
	public function dotAll()
	{
		return "(?s)";
	}

	// String

	public function keepOut()
	{
		return "\K";
	}

	public function unicodeCategory()
	{
		return "\p{L}";
	}

	public function getAnyWordMoreThanOne()
	{
		return "\w+";
	}

	public function getAnyWordMoreThanOneWithoutBlank()
	{
		return "\S+";
	}

	public function alphanumericCharacters()
	{
		return "\w";
	}

	public function nonAlphanumericCharacters()
	{
		return "\W";
	}

	public function wordBoundary()
	{
		return "\b";
	}

	public function digits()
	{
		return "\d";
	}

	public function nonDigits()
	{
		return "\D";
	}

	public function whiteSpaceCharacters()
	{
		return "\s";
	}

	public function nonWhiteSpaceCharacters()
	{
		return "\S";
	}

	// Groupping

	public function balancingGroup($captureSubtract, $expression)
	{
		return "(?<{$captureSubtract}>{$expression})";
	}

	public function branchResetGroup($subexpression)
	{
		return "(?|{$subexpression})";
	}

	public function atomic()
	{
		return "?>";
	}

	public function atomicGroup($name, $subexpression)
	{
		return "({$this->atomic()}{$subexpression})";
	}

	public function namedCapturingGroup($name, $subexpression)
	{
		return "(?P<{$name}>{$subexpression})";
	}

	public function noneCapturingGroup($subexpression)
	{
		return "(?:{$subexpression})";
	}

	// Expression

	public function nodeWithoutAfterSpecifyAttributes($node, $attribute)
	{
		return "(<{$node} .*?)" . $this->noneCapturingGroup("{$attribute}=\".*\"") . "?(.*?\/>)";
	}

	public function nodeWithoutSpecifyAttributes($node, $attribute)
	{
		return "(<{$node} .*?)" . $this->noneCapturingGroup("{$attribute}=\".*\"") . "(.*?\/>)";
	}

	public function positiveLookbehind()
	{
		return "?<=";
	}

	public function negativeLookbehind()
	{
		return "?<!";
	}

	/**
	 * Regex expression of positive lookahead
	 * 
	 * @return string
	 */
	public function positiveLookahead()
	{
		return "?=";
	}

	/**
	 * Regex expression of negative lookahead
	 * 
	 * @return string
	 */
	public function negativeLookahead()
	{
		return "?!";
	}

	// Block

	public function blockTag($name)
	{
		return "<{$name}>.*?<\/{$name}>";
	}

	// Et greta

	public function braces($minimum, $maximum)
	{
		return "\{$minimum,$maximum\}";
	}

	public function negatedCharacterSet()
	{
		return "[^ ]";
	}

	public function zeroOrMoreQuantifier($expression)
	{
		return "{$expression}*";
	}

	public function zeroOrOneQuantifier($expression)
	{
		return "{$expression}?";
	}

	public function oneOrMoreQuantifier($expression)
	{
		return "+";
	}

	public function questionMarkLiteral()
	{
		return $this->zeroOrOneQuantifier("?");
	}

	public function getFileName()
	{
		return "([^.\/]+)\.?[^.\/]*$";
	}

	public function numberFormat()
	{
		return "({$this->positiveLookbehind()}\d)({$this->positiveLookahead()}(\d\d\d)+({$this->negativeLookahead()}\d))";
	}
}
