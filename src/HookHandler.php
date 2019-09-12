<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel;

use Marussia\Hook\Hook;

class HookHandler
{
    private $hookHandler;
    
    public function __construct(Hook $hook)
    {
        $this->hookHandler = $hook;
        $this->hookHandler->setHandlers(Config::get('hooks.handlers', 'handlers'));
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
