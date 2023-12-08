<?php

declare(strict_types=1);

namespace Xanax\Classes\Data;

use Xanax\Exception\MemoryAllocatedException;

use Xanax\Classes\Data\StringHandler;

class HTMLHandler extends StringHandler
{

	public static function getUrlParameter($args)
	{
		$parameter = null;
		$rewriteParams = new \stdClass();
		$func_num = func_num_args();
		$func_get = func_get_args();

		if ($func_get[0] == NULL)
		{
			$i=1;

			while ($i<$func_num)
			{
				if ($parameter)
				{
					if (isset($func_get[$i+1]))
					{
						$parameter .= '&'.$func_get[$i].'='.$func_get[$i+1];
					}
				}
				else
				{
					$parameter .= '?';
					$parameter .= $func_get[$i].'='.$func_get[$i+1];
				}

				$i = $i+2;
			}
		}
		else
		{
			$i=0;
			$get_tmp = $_GET;

			while ($i < $func_num)
			{
				if ($func_get[$i+1]=='')
				{
					unset($get_tmp[$func_get[$i]]);
				}
				else if (isset($func_get[$i+1]))
				{
					$get_tmp[$func_get[$i]] = $func_get[$i+1];
				}

				$i = $i+2;
			}

			foreach ($get_tmp as $key=>$val)
			{
				if ($parameter)
				{
					$parameter .= '&'.$key.'='.$val;
				}
				else
				{
					$parameter .= '?';
					$parameter .= $key.'='.$val;
				}
			}
		}

		$parameter = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'].$parameter : $parameter;

		return $return_url;
	}

	public static function escapeSlash($string)
	{
		return stripslashes($string);
	}

	public static function nlTrim($input)
	{
		$input = preg_replace('/[\r\n]/', '', $input);
		$input = preg_replace('/\t+/', ' ', $input);
		return $input;
	}

	public static function nlslim($input, $max = 2)
	{
		$input = mb_ereg_replace('[\t 　]+(?=[\r\n])', '', $input);
		$replace = str_repeat('$1', $max);
		++$max;
		$regexp = '/(\r\n?|\n) {' . $max . ',}/';
		$input = preg_replace($regexp, $replace, $input);
		$input = str_replace("\t", '    ', $input);
		return $input;
	}

	public static function filterVariable(mixed $string, $type)
	{
		switch ($type)
		{
			case (preg_match('/^MaxLength\((.*\))$/', $type, $matches) ? true : false):
				if (strlen($string) > $matches[1])
				{
					$string = false;
				}

				break;
			case (preg_match('/^Bracket\((.*\))$/', $type, $matches) ? true : false):
				if (isset($matches[1]))
				{
					$regex = $matches[1];
					if (preg_match('/^[A-Za-z0-9]+$/i', $regex, $matches))
					{
						$string = $matches[1];
						$regex = '/^<' . $string . '>([\s\S]*?)<\/' . $string . '>$/i';
					}

					if ($regex && preg_match($regex, $string, $matches))
					{
						if (isset($matches[1]))
						{
							$string = $matches[1];
						}
						else
						{
							$string = false;
						}
					}
					else
					{
						$string = false;
					}
				}
				else
				{
					$string = false;
				}

				break;
			case 'StringNumber':
				if (preg_match('/^[A-Za-z0-9]+$/i', $string, $matches))
				{
					if (isset($matches[1]))
					{
						$string = $matches[1];
					}
					else
					{
						$string = false;
					}
				}
				else
				{
					$string = false;
				}

				break;
			case 'PhoneNumber':
				if (preg_match('/^[0-9]{2,3}-[0-9]{3,4}-[0-9]{4}$/g', $string, $matches))
				{
					if (isset($matches[1]))
					{
						$string = $matches[1];
					}
					else
					{
						$string = false;
					}
				}
				else
				{
					$string = false;
				}

				break;
			case 'URL':
				if (preg_match("/^(http\:\/\/)*[.a-zA-Z0-9-]+\.[a-zA-Z]+$/g", $string, $matches))
				{
					if (isset($matches[1]))
					{
						$string = $matches[1];
					}
					else
					{
						$string = false;
					}
				}
				else
				{
					$string = false;
				}

				break;
			case 'Email':
				if (preg_match("/^[^@]+@[._a-zA-Z0-9-]+\.[a-zA-Z]+$/g", $string, $matches))
				{
					if (isset($matches[1]))
					{
						$string = $matches[1];
					}
					else
					{
						$string = false;
					}
				}
				else
				{
					$string = false;
				}

				break;
			case 'URLParameter':
				if (preg_match('/([^=&?]+)=([^&#]*)/g', $string, $matches))
				{
					if (count($matches) === 1)
					{
						if (isset($matches[1]))
						{
							$string = $matches[1];
						}
						else
						{
							$string = false;
						}
					}
					else if (count($matches) > 1)
					{
						$string = $matches;
					}
				}
				else
				{
					$string = false;
				}

				break;
			case 'Label':
				if (preg_match("/\[([a-zA-Z0-9\s_-]+)\]/i", $string, $matches))
				{
					if (isset($matches[1]))
					{
						$string = $matches[1];
					}
					else
					{
						$string = false;
					}
				}
				else
				{
					$string = false;
				}

				break;
			case 'FunctionName':
				if (preg_match_all("/(\[?[a-zA-Z0-9\s_-]+\]?)/", $string, $matches))
				{
					if (isset($matches[1]))
					{
						$string = $matches[1];
					}
					else
					{
						$string = false;
					}
				}
				else
				{
					$string = false;
				}

				break;
			case 'Deny':
				$string = false;

				break;
			case 'DoubleQuotation':
				if (preg_match('/^"(.*)"$/', $key, $matches))
				{
					if (isset($matches[1]))
					{
						$string = $matches[1];
					}
					else
					{
						$string = false;
					}
				}
				else
				{
					$string = false;
				}

				break;
			case 'SinigleQuotation':
				if (preg_match('/^\'(.*)\'$/', $string, $matches))
				{
					if (isset($matches[1]))
					{
						$string = $matches[1];
					}
					else
					{
						$string = false;
					}
				}
				else
				{
					$string = false;
				}

				break;
			case 'WithOutHTML':
				$string = strip_tags($string);
				break;
			case 'JSON':
				if ( !$this->isJson( $string )) 
				{
					$string = false;
				}

				break;
			case 'Numbers':
				if (!is_numeric($string) || !is_int($string))
			    	{
					if (preg_match('/^(\d[\d\.]+)$/', $key, $matches))
					{
						if (isset($matches[1]))
						{
							$string = $matches[1];
						}
						else
						{
							$string = false;
						}
					}
					else
					{
						$string = false;
					}
				}

				break;
			case 'Number':
				if (!is_numeric($string) || !is_int($string))
			    {
					if (preg_match('/^(\d+)$/', $string, $matches))
					{
						if (isset($matches[1]))
						{
							$string = $matches[1];
						}
						else
						{
							$string = false;
						}
					}
					else
					{
						$string = false;
					}
				}

				break;
			case 'String':
				if (!is_string($string))
			    {
					$string = false;
				}

				break;
			case 'Integer':
				$string = intval($string);

				break;
			case 'Float':
				$string = intval($string);
				$string = (float)sprintf('% u', $string);
				if ($string < 0)
		    	{
					$string = false;
				}

				break;
			case 'Boolean':
				$string = ($string === true) ? true : (($string === false) ? false : false);

				break;
			default:
				break;
		}

		return $string;
	}

	public static function convertSpecialCharactersToHtmlEntities($string) 
	{
		return htmlspecialchars($string, ENT_COMPAT | ENT_HTML401, 'UTF-8', false);
	}

	public function unhtmlSpecialChars($string)
	{
		$entity = array('&quot;', '&#039;', '&#39;', '&lt;', '&gt;', '&amp;');
		$symbol = array('"', "'", "'", '<', '>', '&');
		return str_replace($entity, $symbol, $string);
	}

	public function entityToTag($string, $names)
	{
		$attr = ' ([a-z]+)=&quot;([\w!#$%()*+,\-.\/:;=?@~\[\] ]|&amp|&#039|&#39)+&quot;';
		$name_list = explode(',', $names);
		foreach ($name_list as $name)
		{
			$string = preg_replace_callback("{&lt;($name)(($attr)*)&gt;(.*?)&lt;/$name&gt;}is", array('Utility', 'replace'), $string);
		}

		return $string;
	}

	public static function replaceToHtmlSource($match)
    {
		list($target, $name, $attr) = $match;
		$name = strToLower($name);
		$value = end($match);

		if (strpos($value, '<') !== false)
		{
			return $target;
		}

		if (preg_match('/script|style|link|html|body|frame/', $name))
		{
			return $target;
		}

		if ($attr !== '')
		{
			if (preg_match('/ on|about:|script:|@import|behaviou?r|binding|boundary|cookie|eval|expression|include-source|xmlhttp/i', $attr))
			{
				return $target;
			}

			$attr = str_replace('*/', '*/  ', $attr);
			$attr = str_replace('&quot;', '"', $attr);
			$attr = preg_replace('/ {2,}/', ' ', $attr);
			$attr = str_replace('=" ', '="', $attr);
			$attr = str_replace(' "', '"', $attr);
			$attr = preg_replace('/^ [a-z]+/ie', "strToLower('$0');", $attr);
		}

		return "<$name$attr>$value</$name>";
	}

	public static function nTrim(string $string)
    	{
		return str_replace("\x00", '', $string);
	}

	public static function brToNl(string $string)
	{
		return str_replace('<br />', "\r\n", $string);
	}

	public static function nlToBr(string $string)
	{
		return preg_replace('/\r\n?|\n/', '<br />', $string);
	}

	public function stripTags($string, $tags='')
	{
		if ($tags === '')
		{
			return strip_tags($string);
		}

		$tags = str_replace(',', '><', $tags);
		$tags = "<$tags>";

		return strip_tags($string, $tags);
	}

}