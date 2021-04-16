<?php

return [
    'access_key'   => env('ACCESS_KEY', null),
    'sender_name'  => env('SENDER_NAME', null),
    'set_tag_uri'  => '/v1/sms/addTag',
    'send_sms_uri' => '/v1/sms/send',
    'base_host'    => env('WI_FISHER_HOST', ''),
];