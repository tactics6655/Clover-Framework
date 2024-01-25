<?php

namespace Neko\Classes\Data;

use Neko\Classes\Data\BaseObject as BaseObject;

#[\AllowDynamicProperties]
class IntegerObject extends BaseObject
{

    protected static $raw_data;

    public function __construct($data)
    {
        $this->raw_data = $data;
    }

    public function toInteger()
    {
        return (int)($this->raw_data);
    }

    public function __toString()
    {
        return (string)($this->raw_data);
    }

    public static function isNumeric($string)
    {
        return is_numeric($string);
    }

    public static function toBytes(int $integer)
    {
        $length = strlen($integer);

        $result = "";

        for ($i = $length - 1; $i >= 0; $i--) {
            $result .= chr(floor($integer / pow(256, $i)));
        }

        return $result;
    }

    public static function roundUp($value, $places)
    {
        $mult = pow(10, abs($places));
        return $places < 0 ?
            ceil($value / $mult) * $mult :
            ceil($value * $mult) / $mult;
    }

    //http://sandbox.onlinephpfunctions.com/code/777ac7c4528357ee21426ad7cab6dcdbdd00edf0
    public static function baseConvert($str, $frombase = 10, $tobase = 36)
    {
        $str = trim($str);

        if (intval($frombase) != 10) {
            $len = strlen($str);
            $q = 0;

            for ($i = 0; $i < $len; $i++) {
                $r = base_convert($str[$i], $frombase, 10);
                $q = bcadd(bcmul($q, $frombase), $r);
            }
        } else {
            $q = $str;
        }

        if (intval($tobase) != 10) {
            $s = '';
            while (bccomp($q, '0', 0) > 0) {
                $r = intval(bcmod($q, $tobase));
                $s = base_convert($r, 10, $tobase) . $s;
                $q = bcdiv($q, $tobase, 0);
            }
        } else {
            $s = $q;
        }

        return $s;
    }

    public function length()
    {
        return 1;
    }
}
