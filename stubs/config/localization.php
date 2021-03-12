<?php

return[

    'default_locale' => config('app.fallback_locale'),

    'supported_locales' => [
        'en' => [
            'native' => 'EN',
            'regional_code' => 'en_GB',
            'charset' => 'UTF-8',
            'constants' => ['LC_TIME'],
        ],
        'fr' => [
            'native' => 'FR',
            'regional_code' => 'fr_FR',
            'charset' => 'UTF-8',
            'constants' => ['LC_TIME'],
        ],
        'de' => [
            'native' => 'DE',
            'regional_code' => 'de_DE',
            'charset' => 'UTF-8',
            'constants' => ['LC_TIME'],
        ],
        'it' => [
            'native' => 'IT',
            'regional_code' => 'it_IT',
            'charset' => 'UTF-8',
            'constants' => ['LC_TIME'],
        ]
    ]
];
