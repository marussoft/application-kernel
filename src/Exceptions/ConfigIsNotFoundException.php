<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel\Exceptions;

class ConfigIsNotFoundException extends \Exception
{
    public function __construct(string $configName, string $configFile)
    {
        parent::__construct('Config for ' . $configName . ' is not found in ' . $configFile . '.');
    }
} 
