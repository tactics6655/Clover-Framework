<?php

namespace Xanax\Classes;

use Xanax\Classes\Regex\StringResult as StringResult;
use Xanax\Classes\Regex\ArrayResult as ArrayResult;
use Xanax\Classes\Regex\Executor as Executor;

class Regex
{
  public static function filter(string $pattern, string $replacement, $subject)
  {
  }

  public static function quote(string $string, string $delimiter = NULL)
  {
  }

  public static function match(string $pattern, string $subject): ArrayResult
  {
    $result = Executor::match($pattern, $subject);

    return (new ArrayResult())->getSingleton($result);
  }

  public static function matchAll(string $pattern, string $subject): ArrayResult
  {
    $result = Executor::matchAll($pattern, $subject);

    return (new ArrayResult())->getSingleton($result);
  }

  public static function split(string $pattern, $subject, int $limit = -1)
  {
  }

  public static function replace(string $pattern, string $replacement, $subject, int $limit = -1)
  {
  }
}
