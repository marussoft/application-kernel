<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel;

use Marussia\Config\Config;

abstract class AbstractKernel
{
    protected $extensionCollector;

    protected $routeBuilder;

    protected $response;

    protected $log;

    protected $hook;

    protected $serviceManager;

    protected $config;

    public function __construct(
        ServiceManager $serviceManager,
        HookHandler $hook,
        Config $config,
        RouteBuilder $routeBuilder,
        Response $response,
        Logger $log
    )
    {
        $this->routeBuilder = $routeBuilder;
        $this->response = $response;
        $this->log = $log;
        $this->hook = $hook;
        $this->config = $config;
        $this->serviceManager = $serviceManager;
        $this->serviceManager->set($response);
        $this->serviceManager->set($log);
        $this->serviceManager->set($hook);
        $this->serviceManager->set($config);
    }

    public function view(string $view, array $data = [])
    {
        $this->response->setContent($data);
        $this->response->setView($view);
    }

    public function addHook($hook) : void
    {
        $this->hook->add($hook);
    }

    public function terminate()
    {
        $this->hook->run();
    }
    
    public function instance(string $className, array $params = [], bool $singleton = true)
    {
        return $this->serviceManager->instance($className, $params, $singleton);
    }

}
