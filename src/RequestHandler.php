<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel;

use Marussia\DependencyInjection\Container;

class RequestHandler extends Container
{
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function run(Request $request)
    {
        $handler = $this->instance($this->config->getHandlers($request->getHandler()));
        
        call_user_func_array([$handler, $request->getAction()], [$request]);
    }
}
