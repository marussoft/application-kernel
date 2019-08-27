<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel\Exceptions;

class HandlerAlreadyInitializedException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Handler for current app instance is already initialized.');
    }
} 
