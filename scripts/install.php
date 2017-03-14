#!/usr/bin/env php
<?php
require_once __DIR__. '/../vendor/autoload.php';
\F3\Commuter\DI::fromEnv()['application']->initStorage();
