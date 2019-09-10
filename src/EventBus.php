<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel;

use Marussia\EventBus\Bus;
use Marussia\EventBus\Result;
use Marussia\ApplicationKernel\Config;

class EventBus
{
    private $bus;
    
    public function __construct(Bus $bus)
    {
        $this->bus = $bus;
        $this->bus->setMembersDirPath(Config::get('kernel.bus', 'members_dir_path'));
        $this->bus->setFiltersBinds(Config::get('kernel.bus', 'filters_binds'));
        $this->bus->setLayers(Config::get('kernel.bus', 'layers'));
        $this->bus->setStartingTask(Config::get('kernel.bus', 'starting_task'));
        $this->bus->setTerminatingTask(Config::get('kernel.bus', 'terminating_task'));
        $this->bus->init();
    }
    
    public function run(Request $request) : void
    {
        $this->bus->run($request);
    }
    
    public function result(string $status, $data = null, string $timeout = '') : Result
    {
        return Result::create($status, $data, $timeout);
    }
    
    public function terminate(Request $request, Response $response)
    {
        $this->bus->terminate(compact('request', 'response'));
    }
}
