<?php

declare(strict_types=1);

namespace Neko\Classes\Compression;

use Neko\Enumeration\FileMode;

class GZip
{
    public function compress($data)
    {
        return gzencode($data);
    }

    public function decompress($data)
    {
        return gzdecode($data);
    }
}
