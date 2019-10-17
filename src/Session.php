<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel;

class Session
{
    public function start() : self
    {
        session_start();
        return $this;
    }

    public function set(string $key, $value) : self
    {
        $_SESSION[$key] = $value;
        return $this;
    }
    
    public function has(string $key) : bool
    {
        return isset($_SESSION[$key]);
    }

    public function get(string $key)
    {
        return $_SESSION[$key];
    }
    
    public function unset(string $key) : self
    {
        unset($_SESSION[$key]);
        return $this;
    }
}
