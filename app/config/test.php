<?php
return array_replace_recursive(require __DIR__ . '/common.php', [
    'debug' => true,
    'storage' => [
        'dsn' => 'sqlite::memory:',
    ]
]);
