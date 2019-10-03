<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel\Exceptions;

class KernelExtensionException extends \Exception
{
    public function __construct(string $extensionName, \Throwable $exception)
    {
        $message = 'Kernel extension ' . $extensionName . ' threw an exception ' . $exception->getTraceAsString() . ' line ' . $exception->getLine();
    
        parent::__construct($message);
    }
}
