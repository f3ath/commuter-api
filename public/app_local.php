<?php
if (is_file(__DIR__ . preg_replace('/(\?.*)$/', '', $_SERVER['REQUEST_URI']))) {
    return false;
}

if ('/' === $_SERVER['REQUEST_URI']) {
    return false;
}
require __DIR__ . '/app.php';
