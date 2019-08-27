<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel;

use Marussia\DependencyInjection\Container;
use Marussia\ApplicationKernel\Exceptions\ApplicationHasBeenStartedException;

class App
{
    private static $kernel;

    // Запускает приложение
    public static function initKernel(Config $config) : HttpKernel
    {
        if (static::$kernel === HttpKernel::class) {
            throw new ApplicationHasBeenStartedException();
        }
        
        if (!$config->isReady()) {
            throw new KernelConfigIsNotInitializedException();
        }
        
        $container = new Container();
        
        static::$kernel = new HttpKernel(new ExtensionCollector($config));
        
        // Возвращаем ядро
        return static::$kernel;
    }
    
    public static function view(array $data) : void
    {
        static::$kernel->view($data);
    }
    
    public static function done($data = null) : void
    {
        static::$kernel->done('done', $data);
    }
    
    public static function await(string $timeout) : void
    {
        static::$kernel->await('await', null, $timeout);
    }
    
    public static function fail(string $timeout) : void
    {
        static::$kernel->fail('fail', null, $timeout);
    }
}
