<?php

namespace Xanax\Classes;

class Tokenizer
{

	const OPERATOR     = 1;
	const PARENTHESIES = 2;
	const WORD         = 3;
	const COMMA        = 4;
	const DATATYPE     = 5;

	private $tokens = [];
	private $token  = '';

	private $variableSpecialCharacter = ['-', '_'];

	private $quotes = ['"', "'"];

	private $closableToken = [
		"<",
		"{",
		"("
	];

	private $closeToken = [
		">",
		"}",
		")"
	];

	private $operatorTokens = [
		',' => self::COMMA,
		'?' => self::OPERATOR,
		'=' => self::OPERATOR,
		'&' => self::OPERATOR,
		'%' => self::OPERATOR,
		'+' => self::OPERATOR,
		'-' => self::OPERATOR,
		'*' => self::OPERATOR,
		'/' => self::OPERATOR,
		'<' => self::OPERATOR,
		'>' => self::OPERATOR,
		'(' => self::PARENTHESIES,
		')' => self::PARENTHESIES
	];

	private $patternRegexpr = [
		'keyword' => '/^\w(?<=[A-Za-z0-9_]{1,})$/g'
	];

	private $firstTokens = [
		'string'    => self::DATATYPE,
		'integer'   => self::DATATYPE,
		'boolean'   => self::DATATYPE,
		'float'     => self::DATATYPE,
		'array'     => self::DATATYPE,
		'double'    => self::DATATYPE,
		'long'      => self::DATATYPE,
		'character' => self::DATATYPE,
		'short'     => self::DATATYPE
	];

	const STATE_DEFAULT  = 0;
	const STATE_STRING   = 1;
	const STATE_NUMBER   = 2;
	const STATE_COMMENT  = 3;
	const STATE_WORD     = 4;
	const STATE_OPERATOR = 5;

	public function generateToken(string $string)
	{
		$count = strlen($string);

		$state = self::STATE_DEFAULT;

		$brackets = [];

		// Lexical analysis
		for ($i = 0; $i <= $count; $i++) {
			$character = $string[$i < $count ? $i : $count - 1];

			switch ($state) {
				case self::STATE_DEFAULT:
					if (ctype_alpha($character)) {
						$this->token .= $character;
						$state = self::STATE_WORD;
					} else if (is_numeric($character)) {
						$this->token .= $character;
						$state = self::STATE_WORD;
					} else if (in_array($character, $this->variableSpecialCharacter)) {
						$this->token .= $character;
					} else if (isset($this->operatorTokens[$character])) {
						$this->token .= $character;
						$state = self::STATE_OPERATOR;

						// This token is must be close
						if (in_array($character, $this->closableToken)) {
							$brackets[] = $character;
						}
					} else if (in_array($character, $this->quotes)) {
						$state = self::STATE_STRING;
					} else {
						throw new \Exception('Token is unknown');
					}

					break;
				case self::STATE_OPERATOR:
					if (isset($this->operatorTokens[$character])) {
						$this->token .= $character;

						// When already exists open bracket
						if (count($brackets)) {
							$bracket = array_pop($brackets);

							$key = array_search($bracket, $this->closableToken);

							if ($character !== $this->closeToken[$key]) {
								var_dump($this->closeToken[$key]);
								throw new \Exception('Bracket is not closed');
							}
						}
					} else {
						$this->tokens[] = new TokenObject($this->token, $this->operatorTokens[$this->token]);
						$this->token    = '';
						$i--;

						$state = self::STATE_DEFAULT;
					}

					break;
				case self::STATE_STRING:
					if ($character == '"') {
						$this->tokens[] = new TokenObject($this->token, self::STATE_STRING);
						$this->token    = '';

						$state = self::STATE_DEFAULT;
					} else {
						$this->token .= $character;
					}

					break;
				case self::STATE_WORD:
					if (ctype_alnum($character) || in_array($character, $this->variableSpecialCharacter)) {
						$this->token .= $character;
					} else {
						if (isset($this->operatorTokens[$character])) {
							$this->tokens[] = new TokenObject($this->token, self::STATE_WORD);
						} else {
							$this->tokens[] = new TokenObject($this->token, self::STATE_WORD);
						}

						$this->token = '';
						$i--;

						$state = self::STATE_DEFAULT;
					}

					break;
			}
		}

		if ($state == self::STATE_WORD && !empty($this->token)) {
			$this->tokens[] = new TokenObject($this->token, self::STATE_WORD);
		}

		return $this->tokens;
	}
}
