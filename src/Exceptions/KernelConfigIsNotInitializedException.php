<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel\Exceptions;

class KernelConfigIsNotInitializedException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Kernel config is not initialized.');
    }
} 
