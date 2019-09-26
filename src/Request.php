<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel;

use Marussia\Request\Request as RawRequest;
use Marussia\ApplicationKernel\Exceptions\HandlerAlreadyInitializedException;
use Marussia\ApplicationKernel\Exceptions\ActionAlreadyInitializedException;

class Request extends RawRequest
{
    private $handler = '';
    
    private $action = '';
    
    private $errors = [];
    
    public function setHandler(string $handler) : self
    {
        if (!empty($this->handler)) {
            throw new HandlerAlreadyInitializedException();
        }
        $this->handler = $handler;
        return $this;
    }
    
    public function setAction(string $action) : self
    {
        if (!empty($this->action)) {
            throw new ActionAlreadyInitializedException();
        }
        $this->action = $action;
        return $this;
    }
    
    public function getHandler() : string
    {
        return $this->handler;
    }
    
    public function getAction() : string
    {
        return $this->action;
    }
    
    public function setErrors(array $errors) : self
    {
        array_merge($this->errors, $errors);
        return $this;
    }
    
    public function isValid() : bool
    {
        return empty($this->errors);
    }
    
    public function getErrors() : array
    {
        return $this->errors;
    }
}
