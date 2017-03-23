<?php
declare(strict_types=1);

namespace F3\Commuter;

use F3\PimpleConfig\Config;
use Pimple\Container;

class DI extends Container
{
    public function __construct(string $env = 'prod')
    {
        parent::__construct();
        $this->register(new Config(__DIR__ . '/../app/config', $env));
    }

    public static function fromEnv(): self
    {
        return new self(getenv('APP_ENV') ?: 'prod');
    }
}
