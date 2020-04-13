<?php
return [

    /**
     * Set Imgur application client_id and secret key, endpoint.
     */

    'client_id' => env('IMGUR_CLIENT_ID'),

    'client_secret' => env('IMGUR_CLIENT_SECRET', null),

    'endpoint' => env('IMGUR_ENDPOINT', 'https://api.imgur.com/3/image'),
];
