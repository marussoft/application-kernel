<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel;

use Marussia\Hook\Hook;
use Marussia\Config\Config;

class HookHandler
{
    private $hookHandler;

    public function __construct(Config $config, Hook $hook)
    {
        $this->hookHandler = $hook;
        $this->hookHandler->setHandlers($config->get('hooks.handlers', 'handlers'));
    }

    public function add($hook)
    {
        $this->hookHandler->add($hook);
    }

    public function run()
    {
        $this->hookHandler->run();
    }
}
