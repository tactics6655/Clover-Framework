<?php

declare(strict_types=1);

namespace Xanax\Classes\Compression;

use Xanax\Enumeration\FileMode;

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