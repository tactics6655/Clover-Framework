<?php

namespace Clover\Classes;

class Maya
{
	private static $context = null;
	private $appendText = null;
	private $global_static = 0;
	private $textStartIndex = 0;
	private $textEndIndex = 0;
	private $debug = true;

	public $currentPosition = 0;
	public $textLength = 0;

	public function __construct() {}

	public static function &getInstance()
	{
		static $instance = null;
		if (!$instance) {
			$instance = new maya();
		}

		return $instance;
	}

	public function leftMatch($start, $rule, $text, $mode)
	{
		$self = self::getInstance();

		$check_rule = strpos($rule, '||');

		if ($check_rule !== false) {
			$check_rule = explode('||', $rule);

			foreach ($check_rule as $val) {
				if ($mode == 'equal') {
					if (substr(substr($text, $this->textStartIndex), 0, strlen($val)) == $val) {
						return $start + 1;
					}
				}
			}

			return -1;
		}

		$check_rule = $rule;

		if ($mode == 'equal') {
			if (substr(substr($text, $this->textStartIndex), 0, strlen($check_rule)) == $check_rule) {
				return $start + 1;
			}
		}

		return -1;
	}

	public function rightMatch($start, $rule, $text, $mode)
	{
		$self = self::getInstance();
		$self->global_static = false;

		$check_rule_split = strpos($rule, '||');

		if ($check_rule_split !== false) {
			$check_rule = explode('||', $rule);
			foreach ($check_rule as $val) {
				switch ($mode) {
					case "like":
						$check_arr = ($this->appendText == null) ?
							strpos(substr($text, $self->textStartIndex), $val) :
							strpos(substr($text, $self->textStartIndex), $self->appendText . $val);

						if ($check_arr !== false) {
							return $start + 1;
						}

						break;
					case "equal":
						$rep_a = ($this->appendText == null) ?
							substr(substr($text, $self->textStartIndex), strlen(substr($text, $self->textStartIndex)) - strlen($val), strlen($val)) :
							substr(substr($text, $self->textStartIndex), (strlen(substr($text, $self->textStartIndex)) - strlen($val)) - strlen($this->appendText), strlen($val) + strlen($this->appendText));

						$rep_b = ($this->appendText == null) ? $val : $this->appendText . $val;

						if ($rep_a == $rep_b) {
							return $start + 1;
						}

						break;
					default:
						break;
				}
			}

			return -1;
		} else {
			$check_rule = $rule;

			if ($mode == 'like') {
				$check_rule = ($this->appendText == null) ?
					strpos(substr($text, $this->textStartIndex), $check_rule) :
					strpos(substr($text, $this->textStartIndex), $this->appendText . $check_rule);

				if ($check_rule !== false) {
					$self->textStartIndex = $check_rule;

					return $start + 1;
				}
			} else if ($mode == 'equal') {
				if (substr(substr($text, $self->textStartIndex), strlen(substr($text, $self->textStartIndex)) - strlen($check_rule), strlen($check_rule)) == $check_rule) {
					return $start + 1;
				}
			}

			return -1;
		}
	}

	public function line_execute_match_callback() {}

	public function line_pass($start, $rule, $text, $passage = 0)
	{
		$self = self::getInstance();

		$check_rule = strpos($rule, '||');

		if ($check_rule !== false) {
			$check_rule = explode('||', $rule);

			foreach ($check_rule as $val) {
				$pattern_pos = strpos($text, $val, $start);
				if ($pattern_pos !== false) {
					return $self->line_pass($pattern_pos + 1, $rule, $text, $passage == 0 ? strlen($rule) : $passage);
				}
			}

			$self->textStartIndex = $start;

			return strlen($rule) + 1;
		}

		$pattern_pos = strpos($text, $rule, $start);

		if ($pattern_pos !== false) {
			return $self->line_pass($pattern_pos + 1, $rule, $text);
		}

		$self->textStartIndex = $start;
		return $passage == 0 ? strlen($rule) + 1 : $passage + 1;
	}

	public function line_add($start, $rule)
	{
		$this->appendText = $rule;

		return $start + 1;
	}

	public function line_execute($start, $rule, $pattern, $text)
	{
		$self        = self::getInstance();
		$pattern_pos = strpos($rule, $pattern);
		$escape_pos  = substr($rule, $pattern_pos + 1, 1);

		if ($pattern_pos === false) {
			return -1;
		}

		if ($escape_pos === '^') {
			$self->line_execute($pattern_pos, substr($rule, $pattern_pos), $pattern, $text);
		} else {
			switch ($pattern):
				case '+':
					return $self->line_add($pattern_pos, substr($rule, $start, $pattern_pos));
				case '$':
					return $self->leftMatch($pattern_pos, substr($rule, $start, $pattern_pos), $text, 'equal');
				case '#':
					return $self->rightMatch($pattern_pos, substr($rule, $start, $pattern_pos), $text, 'like');
				case '!':
					return $self->rightMatch($pattern_pos, substr($rule, $start, $pattern_pos), $text, 'equal');
				case '@':
					return $self->line_pass($pattern_pos, substr($rule, $start, $pattern_pos), $text);
				default:
					break;
			endswitch;
		}
	}

	public static function execute($rule, $text, $debug = false)
	{
		$self = self::getInstance();

		$self->debug  = $debug;
		$self->textStartIndex = 0;

		$matchRuleCharacters = ['!', '#', '@', '$', '+'];

		$ruleLength = strlen($rule);
		$textLength = strlen($text);

		if ($ruleLength == 0) {
			return;
		}

		if ($textLength == 0) {
			return;
		}

		for ($i = 0; $i < $ruleLength; $i++) {
			$pattern_pass = substr($rule, $i, 1);

			if (!in_array($pattern_pass, $matchRuleCharacters)) {
				continue;
			}

			$self_position = $self->line_execute(0, substr($rule, $i + 1), $pattern_pass, $text);
			if ($self_position == -1) {
				return false;
			}

			$i = $i + $self_position;
		}

		return true;
	}
}
