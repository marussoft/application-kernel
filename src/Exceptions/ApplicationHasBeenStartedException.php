<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel\Exceptions;

class ApplicationHasBeenStartedException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Application has been started.');
    }
}
