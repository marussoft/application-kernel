<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel\Exceptions;

class ConfigFileIsNotFoundException extends \Exception
{
    public function __construct(string $configFile)
    {
        parent::__construct('Config file ' . str_replace('.', '/', $configFile) . ' is not found.');
    }
}
