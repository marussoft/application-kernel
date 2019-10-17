<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel;

use Marussia\Config\Config;

class ExtensionCollector
{
    private $extensionsBinds = [];

    public function __construct(Config $config)
    {
        $this->extensionsBinds = $config->get('kernel.extensions', 'extensions');
    }

    public function getExtensions() : array
    {
        return $this->extensionsBinds;
    }

    public function extensionsIsExists() : bool
    {
        return !empty($this->extensionsBinds);
    }
}
