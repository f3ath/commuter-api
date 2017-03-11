<?php
return [
    'services' => [
        'application' => __DIR__ . '/services/application.php',
        'storage' => __DIR__ . '/services/storage.php',
        'web_app' => __DIR__ . '/services/web_app.php',
    ],
    'debug' => false,
    'storage' => [
        'dsn' => 'sqlite:/tmp/db.sq3',
        'lifetime' => 60,
    ]
];
