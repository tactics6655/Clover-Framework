<?php

declare(strict_types=1);

namespace Xanax\Classes\HTML;

class Handler
{
	public static function getInputTag($type, $name, $value, $list = array())
	{
		$html = "";
		
		switch ($type) {
			case "option":
				foreach ($list as $val) {
					$html .= "<option " . (($value == $val) ? "selected " : "") . "value=\"{$val}\">{$val}</option>";
				}

				$html = "<select name=\"{$name}\">{$html}</select>";
				break;
			case "textarea":
				$html = "<textarea rows=\"4\" cols=\"50\" name=\"{$name}\" value=\"{$value}\">{$value}</textarea>";
				break;
			case "color":
				$html = "<input type=\"color\" name=\"{$name}\" value=\"{$value}\">";
				break;
			case "date":
				$html = "<input type=\"date\" name=\"{$name}\" value=\"{$value}\">";
				break;
			case "datetime-local":
				$html = "<input type=\"datetime-local\" name=\"{$name}\" value=\"{$value}\">";
				break;
			case "month":
				$html = "<input type=\"month\" name=\"{$name}\" value=\"{$value}\">";
				break;
			case "number":
				$html = "<input type=\"number\" name=\"{$name}\" value=\"{$value}\">";
				break;
			case "password":
				$html = "<input type=\"password\" name=\"{$name}\" value=\"{$value}\">";
				break;
			case "time":
				$html = "<input type=\"time\" name=\"{$name}\" value=\"{$value}\">";
				break;
			case "week":
				$html = "<input type=\"week\" name=\"{$name}\" value=\"{$value}\">";
				break;
			case "file":
				$html = "<input type=\"file\" name=\"{$name}\" value=\"{$value}\">";
				break;
			case "radio":
				foreach ($list as $val) {
					$html .= "<input " . (($value == $val) ? "checked " : "") . "name=\"{$name}\" type=\"radio\" value=\"{$val}\">{$val}</input>";
				}

				break;
			default:
				$html = "<input style=\"width:100%\" type=\"text\" class=\"text itx\" name=\"{$name}\" value=\"{$value}\"></input>";
				break;
		}

		return $html;
	}

	public static function generateParameter($attributes = [])
	{
		$result = '';

		foreach ($attributes as $key => $val) {
			$pair = sprintf("'%s'", $val);
			if ($result) {
				$result = $result . ',' . $pair;
			} else {
				$result = $pair;
			}
		}

		return $result;
	}

	public static function generateAudioTag($source, $tags = [])
	{
		return self::generateElement('audio', '', $tags, false);
	}

	public static function generateElement($type, $content, $attributes = [], $close = false)
	{
		$html = sprintf('%s%s', '<', $type);

		if (empty($attributes)) {
			$html .= '';
		} else if (is_string($attributes)) {
			$html .= ' ' . $attributes;
		} else if (is_array($attributes)) {
			foreach ($attributes as $key => $val) {
				if (isset($key) && !is_bool($val)) {
					$pairs[] = sprintf('%s="%s"', $key, $val);
				} else if (isset($key) && is_bool($val) && $val) {
					$pairs[] = sprintf('%s', $key);
				}
			}

			$html .= ' ' . implode(' ', $pairs);
		}

		return $close ? sprintf('%s>', $html) : sprintf('%s>%s</%s>', $html, $content, $type);
	}
}
