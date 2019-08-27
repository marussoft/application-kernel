<?php

declare(strict_type=1);

namespace Marussia\ApplicationKernel;

use Marussia\Request\Request as RawRequest;
use Marussia\ApplicationKernel\Exceptions\HandlerAlreadyInitializedException;
use Marussia\ApplicationKernel\Exceptions\ActionAlreadyInitializedException;

class Request extends RawRequest
{
    private $handler;
    
    private $action;

    public function setHandler(string $handler) : void
    {
        if (!empty($this->handler)) {
            throw new HandlerAlreadyInitializedException();
        }
        $this->handler = $handler;
    }
    
    public function setAction(string $action) : void
    {
        if (!empty($this->action)) {
            throw new ActionAlreadyInitializedException();
        }
        $this->action = $action;
    }
    
    public function getHandler() : string
    {
        return $this->handler;
    }
    
    public function getAction() : string
    {
        return $this->action;
    }
}
