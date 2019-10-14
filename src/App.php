<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel;

use Marussia\Config\Config;
use Marussia\ApplicationKernel\Exceptions\ApplicationHasBeenStartedException;
use Marussia\ApplicationKernel\Exceptions\KernelConfigIsNotInitializedException;

class App
{
    private static $kernel = null;

    // Запускает приложение
    public static function initKernel(Config $config) : HttpKernel
    {
        if (static::$kernel !== null) {
            throw new ApplicationHasBeenStartedException();
        }

        $container = new Container();
        $container->set($config);
        
        $providers = $config->getAll('app.providers');
        
        $container->instance(ServiceManager::class, $providers)

        static::$kernel = $container->instance(HttpKernel::class);

        // Возвращаем ядро
        return static::$kernel;
    }

    public function view(string $view, array $data = []) : void
    {
        static::$kernel->view($view, $data);
    }

    public function hook($hook) : void
    {
        static::$kernel->addHook($hook);
    }

    public function getContainer() : Container
    {
        return new Container();
    }
}
