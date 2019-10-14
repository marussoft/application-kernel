<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel;

class ServiceManager extends Container
{
    public function __construct(Response $response, Logger $logger)
    {
        $this->set($response);
        $this->set($logger);
    }
}
