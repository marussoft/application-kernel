<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel\Exceptions;

class ActionAlreadyInitializedException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Action for current app instance is already initialized.');
    }
}
