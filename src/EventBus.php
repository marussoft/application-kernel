<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel;

use Marussia\EventBus\Bus;
use Marussia\EventBus\Result;
use Marussia\Request\Request;
use Marussia\ApplicationKernel\Config;

class EventBus
{
    private $bus;
    
    private $starterMember;
    
    private $action;
    
    private $members;
    
    public function __construct(Bus $bus)
    {
        $this->bus = $bus;
        $this->bus->setMembersDirPath(Config::get('kernel.bus', 'members_dir_path'));
        $this->bus->setFiltersBinds(Config::get('kernel.bus', 'filters_binds'));
        $this->bus->setLayers(Config::get('kernel.bus', 'layers'));
        $this->bus->init();
    }
    
    public function run(Request $request) : void
    {
        $this->bus->run($this->starterMember, $this->action, $request);
    }
    
    public function result(string $status, $data = null, string $timeout = '') : Result
    {
        return Result::create($status, $data, $timeout);
    }
}
