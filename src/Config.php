<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel;

use Marussia\ApplicationKernel\Exceptions\ConfigIsNotFoundException;
use Marussia\ApplicationKernel\Exceptions\ConfigFileIsNotFoundException;

class Config
{
    private static $instance;

    private $rootPath;
    
    private $configDir;
    
    private $configures = [];
    
    private $isReady = false;

    public function __construct(string $rootPath, string $configDir)
    {
        $this->rootPath = $rootPath;
        
        $this->configDir = $rootPath . '/' . $configDir;
        
        static::$instance = $this;
        
        $this->isReady = true;
    }
    
    public function getAll(string $configName) : array
    {
        if (array_key_exists($this->configures[$configName])) {
            return $this->configures[$configName];
        }
    
        $configPath = $this->configDir . '/' . str_replace('.', '/', $configName) . '.php';
        
        if (!is_file($configPath)) {
            throw new ConfigFileIsNotFoundException($configName);
        }
        
        $this->configures[$configName] = include $configPath;
        return $this->configures[$configName];
    }
    
    public static function get(string $configFile, string $configName)
    {
        $configArray = static::$instance->getAll($configFile);
        
        if (!array_key_exists($configName, $configArray)) {
            throw new ConfigIsNotFoundException($configName, $configFile);
        }
        return $configArray[$configName];
    }
    
    public function isReady() : bool
    {
        return $this->isReady;
    }
}
