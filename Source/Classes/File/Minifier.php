<?php

namespace Neko\Classes;

class Minifier
{
    public static function minifyCssContent($filename, $cssBuff)
    {
        $filename = preg_replace('/(\?[a-z0-9]{1,500})/', '', $filename);

        $cssBuff = preg_replace_callback('/url\(([^\)]+)\)/i', function ($matches) use ($filename) {
            $url = trim($matches[1], '\'"');

            if (!preg_match("/^\//i", $url)) {
                return sprintf('url("%s/%s");', dirname($filename), $url);
            } else if (preg_match("/^data:/i", $url)) {
                return sprintf('url("%s");', $url);
            } else if (preg_match("/^\\//i", $url)) {
                return sprintf('url("%s");', $url);
            }
        }, $cssBuff);

        $cssBuff = preg_replace("@/\s*\*.*?\*/\s*|\s+@s", " ", $cssBuff);

        return $cssBuff;
    }
}
