<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel;

use Marussia\Config\Config;
use Marussia\ApplicationKernel\Exceptions\ApplicationHasBeenStartedException;
use Marussia\ApplicationKernel\Exceptions\KernelConfigIsNotInitializedException;

class App
{
    private $kernel = null;

    private static $started = false;

    public function __construct(HttpKernel $kernel)
    {
        $this->kernel = $kernel;
    }
    
    // Запускает приложение
    public static function initKernel(Config $config) : HttpKernel
    {
        if (static::$started) {
            throw new ApplicationHasBeenStartedException();
        }

        $providers = $config->getAll('app.providers');

        $container = new Container($providers);
        $container->set($config);

        $serviceManager = $container->instance(ServiceManager::class, [$providers]);
        
        $kernel = $container->instance(HttpKernel::class);
        
        $app = new static($kernel);
        
        $serviceManager->set($app);
        
        static::$started = true;

        return $kernel;
    }

    public function view(string $view, array $data = []) : void
    {
        $this->kernel->view($view, $data);
    }

    public function hook($hook) : void
    {
        $this->kernel->addHook($hook);
    }

    public function instance(string $className, array $params = [], bool $singleton = true)
    {
        return $this->kernel->instance($className, $params, $singleton);
    }
}
