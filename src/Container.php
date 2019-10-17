<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel;

use Marussia\Config\Config;
use Marussia\DependencyInjection\Container as DependencyInjection;

class Container extends DependencyInjection
{
    public function __construct(array $providers = [])
    {
        $this->providers = $providers;
    }

    protected function instanceSingleClass(string $class) : void
    {
        $params = [];

        if ($this->hasDefinition($class)) {
            return;
        }

        foreach ($this->dependencies[$class] as $dep) {

            if ($this->hasDefinition($dep)) {
                continue;
            }
        
            if (array_key_exists($dep, $this->providers)) {
                $container = clone($this);
                $provider = $container->instance($this->providers[$dep]);
                $params = $provider->getParams();
                
                $constructor = $this->reflections[$dep]->getConstructor();
                
                if ($constructor !== null) {
                    $this->setDefinition($dep, $this->reflections[$dep]->newInstanceArgs($params));
                } else {
                    $this->setDefinition($dep, $this->reflections[$dep]->newInstance());
                }

                $provider->prepare($this->getDefinition($dep));
                    
            } else {
                $this->setDefinition($dep, $this->reflections[$dep]->newInstance());
            }

        }

        $this->instanceClass($class, $this->dependencies[$class]);
    }

    protected function instanceClass(string $class, array $deps = []) : void
    {
        $dependencies = [];

        foreach ($deps as $dep) {
            $dependencies[] = $this->getDefinition($dep);
        }

        if (!empty($this->params)) {
            $dependencies = array_merge($dependencies, $this->params);
        }

        if (array_key_exists($class, $this->providers)) {
            $container = clone($this);
            $provider = $container->instance($this->providers[$class]);
            $dependencies = array_merge($dependencies, $provider->getParams());
        }

        $this->setDefinition($class, $this->reflections[$class]->newInstanceArgs($dependencies));
    }

    // Рекурсивно инстанцирует зависимости
    protected function instanceRecursive(string $class, array $deps = []) : void
    {

        $dependencies = [];

        $params = [];

        foreach ($deps as $dep) {

            if ($this->hasDefinition($dep)) {
                $dependencies[] = $this->getDefinition($dep);
                continue;
            } 
    
            if (isset($this->dependencies[$dep])) {

                if ($this->hasDefinition($dep)) {
                    $dependencies[] = $this->getDefinition($dep);

                } elseif ($this->getDefinition($dep) !== null) {

                    $this->instanceRecursive($dep, $this->getDefinition($dep));

                } else {
                    $this->instanceSingleClass($dep);
                }

            } else {

                if ($this->hasDefinition($dep)) {
                    $dependencies[] = $this->getDefinition($dep);
                    continue;
                }

                if (array_key_exists($dep, $this->providers)) {
                    $container = clone($this);
                    $provider = $container->instance($this->providers[$dep]);
                    $params = $provider->getParams();
                    
                    $constructor = $this->reflections[$dep]->getConstructor();

                    if ($constructor !== null) {
                        $this->setDefinition($dep, $this->reflections[$dep]->newInstanceArgs($params));
                    } else {
                        $this->setDefinition($dep, $this->reflections[$dep]->newInstance());
                    }
                    
                    $provider->prepare($this->getDefinition($dep));
                    
                } else {
                    $this->setDefinition($dep, $this->reflections[$dep]->newInstance());
                }

            }

            if (!in_array($this->getDefinition($dep), $dependencies, true)) {
                $dependencies[] = $this->getDefinition($dep);
            }
        }

        $this->setDefinition($class, $this->reflections[$class]->newInstanceArgs($dependencies));
    }
}
