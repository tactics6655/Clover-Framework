<?php

declare(strict_types=1);

namespace Clover\Classes\Compression;

use Clover\Enumeration\FileMode;

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
