<?php

return [
    'any' => '^.*$',
    'digit' => '^\d{1,}$',
    'word' => '^\w{1,}$',
    'alphabet' => '^[a-zA-Z]{1,}$',
    'hiraganaorkatakana' => '^[ぁ-んァ-ン]{1,}$',
    'french' => '^[a-zàâçéèêëîïôûùüÿñæœ .-]*$',
    'hiragana' => '^[ぁ-ん]{1,}$',
    'korean' => '^[\u3131-\uD79D]{1,}$'
];