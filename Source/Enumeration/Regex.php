<?php

namespace Neko\Enumeration;

enum Regex: string
{

    case NUMBER = 'number';

    case ALPHABET = 'alphabet';

    case ALPHABET_NUMBER = 'alphabet_number';

    case PHONE_NUMBER = 'phone_number';

    case JAPANESE = 'japanese';

    case KANJI = 'kanji';

    case HIRAGANA = 'hiragana';

    case KATAKANA = 'katakana';

    case EMAIL = 'email';

    case KOREAN = 'korean';

    case BASE64 = 'base64';

    case KOREAN_ENGLISH = 'korean_english';
}
