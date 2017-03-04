<?php
namespace F3\Tracker\Web;

use F3\SilexConfig\Config;

class Application extends \Silex\Application
{
    public function __construct(string $env = 'prod')
    {
        parent::__construct();
        (new Config(__DIR__ . '/../../app/config'))
            ->configure($this, $env);
    }
}
