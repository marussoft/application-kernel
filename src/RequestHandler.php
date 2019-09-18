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
            
            if (!$this->hasDefination($dep)) {
                $this->setDefination($dep, $this->reflections[$dep]->newInstanceArgs($params));
            }
            $provider::prepare($this->getDefination($dep));
        }
        
        $this->instanceClass($class, $this->dependencies[$class]);
    }
    
    protected function instanceClass(string $class, array $deps = []) : void
    {
        $dependencies = [];
        
        foreach ($deps as $dep) {
            $dependencies[] = $this->getDefination($dep);
        }
        
        if (!empty($this->params)) {
            $dependencies = array_merge($dependencies, $this->params);
        }
        
        $provider = Config::get('app.providers', $class);
        
        if (!empty($provider)) {
            $dependencies = array_merge($dependencies, $provider::getParams());
        }
        
        $this->setDefination($class, $this->reflections[$class]->newInstanceArgs($dependencies));
    }
}
