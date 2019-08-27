<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel;

use Marussia\DependencyInjection\Container;

class ExtensionCollector extends Container
{
    private $extensionsBinds = [];

    public function __construct()
    {
        $this->extensionBinds = Config::get('kernel.extensions');
    }

    public function getExtensions() : array
    {
        $extensions = [];
    
        foreach($this->extensionsBinds as $extensionName => $class) {
            $extensions[$extensionName] = $this->instance($class);
        }
        
        return $extensions;
    }
    
    public function extensionsIsExists() : bool
    {
        return !empty($this->extensionsBinds);
    }
}
