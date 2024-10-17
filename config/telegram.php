<?php

return [
    'bots' => [
        'lgcfc_bot' => [
            'token' => env('TELEGRAM_BOT_TOKEN', 'YOUR-BOT-TOKEN'),
        ],
    ],

    'default' => 'lgcfc_bot',

    'chat_id' => env('TELEGRAM_CHAT_ID', 'CHAT-ID')
];
