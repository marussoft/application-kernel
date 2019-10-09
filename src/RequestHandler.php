<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel;

use Marussia\Config\Config;

class RequestHandler extends Container
{
    public function run(Request $request)
    {
        $handlerClass = Config::get('handlers.' . strtolower($request->getHandler()), $request->getAction());
        if (!empty($handlerClass)) {
            $handler = $this->instance($handlerClass);
            call_user_func_array([$handler, 'run'], [$request]);
        }
    }
}
