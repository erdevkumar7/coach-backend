<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    //'allowed_origins' => ['*'], // Allow all origins (development only!)
    // For production, use specific origin:
    'allowed_origins' => ['http://localhost:3000', 'https://votivereact.in/coachsparkle'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,
];