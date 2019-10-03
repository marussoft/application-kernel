<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel\Exceptions;

use Marussia\ApplicationKernel\Request;

class HandlerProcessException extends \Exception
{
    public function __construct(Request $request, \Throwable $exception)
    {
        $message = 'Handler ' . $request->getHandler() . ' threw an exception ' . $exception->getTraceAsString() . ' on action ' . $request->getAction() . ' line ' . $exception->getLine();
    
        parent::__construct($message);
    }
}
