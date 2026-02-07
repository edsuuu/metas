<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Web Push VAPID Keys
    |--------------------------------------------------------------------------
    |
    | Chaves VAPID para Web Push Notifications.
    | Gere usando: php artisan tinker
    | >>> $vapid = Minishlink\WebPush\VAPID::createVapidKeys();
    |
    | Depois adicione ao .env:
    | VAPID_PUBLIC_KEY=sua_chave_publica
    | VAPID_PRIVATE_KEY=sua_chave_privada
    | VAPID_SUBJECT=mailto:seu@email.com
    |
    */

    'vapid' => [
        'public_key' => env('VAPID_PUBLIC_KEY'),
        'private_key' => env('VAPID_PRIVATE_KEY'),
        'subject' => env('VAPID_SUBJECT', 'mailto:' . env('MAIL_FROM_ADDRESS', 'hello@example.com')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Push Notification Defaults
    |--------------------------------------------------------------------------
    */

    'defaults' => [
        'ttl' => 2419200, // 4 weeks in seconds
        'urgency' => 'normal', // very-low, low, normal, high
        'topic' => null,
    ],
];
