#!/usr/bin/env php
<?php
require_once __DIR__. '/../vendor/autoload.php';
$storage = \F3\Commuter\DI::fromEnv()['storage']->clean();
