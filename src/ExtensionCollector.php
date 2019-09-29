<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel;

use Marussia\Config\Config;
use Marussia\DependencyInjection\Container;

class ExtensionCollector extends Container
{
    private $extensionsBinds = [];

    public function __construct()
    {
        $this->extensionsBinds = Config::get('kernel.extensions', 'extensions');
    }

    public function getExtensions() : array
    {
        $extensions = [];
        foreach($this->extensionsBinds as $extensionName => $className) {
            $extensions[$extensionName] = $this->instance($className);
        }
        return $extensions;
    }

    public function extensionsIsExists() : bool
    {
        return !empty($this->extensionsBinds);
    }
}
