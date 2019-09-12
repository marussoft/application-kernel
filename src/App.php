<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel;

use Marussia\DependencyInjection\Container;
use Marussia\EventBus\Result;
use Marussia\ApplicationKernel\Exceptions\ApplicationHasBeenStartedException;
use Marussia\ApplicationKernel\Exceptions\KernelConfigIsNotInitializedException;

class App
{
    private static $kernel;

    // Запускает приложение
    public static function initKernel(Config $config) : HttpKernel
    {
        if (static::$kernel === HttpKernel::class) {
            throw new ApplicationHasBeenStartedException();
        }
        
        $container = new Container();
        
        if (!$config->isReady()) {
            throw new KernelConfigIsNotInitializedException();
        }
        
        $container->set($config);
        
        static::$kernel = $container->instance(HttpKernel::class);
        
        // Возвращаем ядро
        return static::$kernel;
    }
    
    public static function view(string $view, array $data = []) : void
    {
        static::$kernel->view($view, $data);
    }
    
    public static function hook($hook) : void
    {
        static::$kernel->addHook($hook);
    }
    
    public static function response() : Response
    {
        return static::$kernel->getResponse();
    }
    
    public static function getContainer() : Container
    {
        return new Container();
    }
}
