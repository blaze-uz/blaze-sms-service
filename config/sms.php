<?php

return [
    'default' => 'blaze_core',

    'drivers' => [
        'blaze_core' => [
            'central_token_app_name' => env('BLAZE_SMS_TOKEN_APP_NAME', 'blaze_core_sms_token'),
            'channel' => 'eskiz',
            'endpoint' => env('BLAZE_SMS_ENDPOINT', 'https://api.blaze.uz/sms/send'),
            'template_moderation_endpoint' => env('BLAZE_SMS_TEMPLATE_ENDPOINT', 'https://api.blaze.uz/sms/template/moderation'),
        ],
    ],
];
