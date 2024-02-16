<?php

namespace Neko\Enumeration;

enum Encoding: string
{
    case UTF_7 = 'UTF-7';

    case UTF_8 = 'UTF-8';

    case UTF_16_BIG_ENDIAN = 'UTF-16BE';

    case UTF_16_LITTLE_ENDIAN = 'UTF-16LE';

    case UTF_32_BIG_ENDIAN = 'UTF-32BE';

    case UTF_32_LITTLE_ENDIAN = 'UTF-32LE';

    case KOI8_R = 'KOI8-R';

    case WINDOWS_1251 = 'Windows-1251';

    case CP936 = 'CP936';

    case EUC_KR = 'EUC-KR';

    case JIS = 'JIS';

    case EUC_JP = 'EUC-JP';

    case ASCII = 'ASCII';

    case HTML_ENTITIES = 'HTML-ENTITIES';
}
