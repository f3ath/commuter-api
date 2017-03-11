<?php
require_once __DIR__.'/../vendor/autoload.php';
\F3\Commuter\DI::fromEnv()['web_app']->run();
