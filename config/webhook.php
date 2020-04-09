<?php

return [
    'key'         => env('CALL_API_KEY', 'jd8od1szqvralsk7ajuy2ypht9rx8ked'),
    'user'        => env('CALL_API_USER', 'isahmetb@gmail.com'),
    'my_url'      => env('APP_URL', 'https://oracle-frontend-laravel-bi.dev.pdn.kz/api/calls'),
    'url'         => env('CALL_API_URL', ''),
    'actions'     => [
        'subscribe'   => 'webhook.subscribe',
        'unsubscribe' => 'webhook.unsubscribe',
        'list'        => 'webhook.list',
    ],
    'call_events' => [
        'call.start'  => 'start',
        'call.answer' => 'answer',
        'call.finish' => 'finish',
    ],
    'reports'     => [
        'columns' => [
            'start_time'    => 'Дата',
            'client_number' => 'Телефон',
            'direction'     => 'Направления',
            'duration'      => 'Длительность',
            'answer'        => 'Отвечен',
            'link'          => 'Запись',
        ],
        'direction' => [
            0 => 'Входящий',
            1 => 'Исходящий'
        ]
    ],
];
