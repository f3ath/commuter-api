<?php
return [
    'services' => [
        'app' => __DIR__ . '/services/app.php',
        'routing' => __DIR__ . '/services/routing.php',
    ],
    'debug' => false,
    'storage' => [
        'dsn' => 'sqlite:/tmp/db.sq3'
    ]
];
