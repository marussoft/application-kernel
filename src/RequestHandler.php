<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel;

use Marussia\DependencyInjection\Container;

class RequestHandler extends Container
{
    private $config;

    public function run(Request $request)
    {
        $handlerClass = Config::get('handlers.' . strtolower($request->getHandler()), $request->getAction());
        if (!empty($handlerClass)) {
            $handler = $this->instance($handlerClass);
            call_user_func_array([$handler, 'run'], [$request]);
        }
    }
    
    protected function instanceSingleClass(string $class) : void
    {
        $params = [];
    
        foreach ($this->dependencies[$class] as $dep) {
            
            $provider = Config::get('app.providers', $dep);
            
            if (!empty($provider)) {
                $params = $provider::getParams();
            }
            
            if (!$this->hasDefinition($dep)) {
                $this->setDefinition($dep, $this->reflections[$dep]->newInstanceArgs($params));
            }

            if (!empty($provider)) {
                $provider::prepare($this->getDefinition($dep));
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
        
        $provider = Config::get('app.providers', $class);
        
        if (!empty($provider)) {
            $dependencies = array_merge($dependencies, $provider::getParams());
        }
        
        $this->setDefinition($class, $this->reflections[$class]->newInstanceArgs($dependencies));
    }
    
    // Рекурсивно инстанцирует зависимости
    protected function instanceRecursive(string $class, array $deps = []) : void
    {
        $dependencies = [];
    
        $params = [];
        
        foreach ($deps as $dep) {
            
            if (isset($this->dependencies[$dep])) {
            
                if ($this->hasDefinition($dep)) {
                    $dependencies[] = $this->getDefinition($dep);
                } elseif ($this->getDefinition($dep) !== null) {
                    $this->instanceRecursive($dep, $this->getDefinition($dep));
                } else {
                    $this->instanceSingleClass($dep);
                }

            } else {
            
                $provider = Config::get('app.providers', $dep);
                
                if (!empty($provider)) {
                    $params = $provider::getParams();
                }
            
                $this->setDefinition($dep, $this->reflections[$dep]->newInstanceArgs($params));
            }
            
            $dependencies[] = $this->getDefinition($dep);
        }
        
        $this->setDefinition($class, $this->reflections[$class]->newInstanceArgs($dependencies));
    }
}
