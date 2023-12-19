<?php

namespace Xanax\Classes\Data;

class CodePoint
{
    public static function isCJK($codePoint)
    {
        return ($codePoint >= 0x1100 && ($codePoint <= 0x115f || ($codePoint >= 0x2e80 && $codePoint <= 0xa4cf && $codePoint != 0x303f)));
    }

    public static function isFullWidthForms($codePoint)
    {
        return ($codePoint >= 0x1100 && $codePoint >= 0xff00 && $codePoint <= 0xff60);
    }

}