<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel;

use Marussia\Config\Config;
use Marussia\DependencyInjection\Container;

class RequestHandler extends Container
{
    public function run(Request $request)
    {
        $this->providers = Config::getAll('app.providers');
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

            if (array_key_exists($dep, $this->providers)) {
                $params = $this->providers[$dep]::getParams();
            }

            if (!$this->hasDefinition($dep)) {
                $this->setDefinition($dep, $this->reflections[$dep]->newInstanceArgs($params));
            }

            if (array_key_exists($dep, $this->providers)) {
                $this->providers[$dep]::prepare($this->getDefinition($dep));
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
            $dependencies = array_merge($dependencies, $this->providers[$dep]::getParams());
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

                if (array_key_exists($dep, $this->providers)) {
                    $params = $this->providers[$dep]::getParams();
                }

                // Получаем конструктор
                $constructor = $this->reflections[$dep]->getConstructor();

                if ($constructor !== null) {
                    $this->setDefinition($dep, $this->reflections[$dep]->newInstanceArgs($params));
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
