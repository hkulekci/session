<?php
namespace Session;
use Exception;

class Session implements \Session\SessionInterface
{
    private $instance = null;
    private $instance_name = "";

    public function __construct(array $config = array())
    {
        if (!$config){
            throw new Exception("Session config not found", 1);
        }
        
        $driver = ((isset($config['driver']) && $config['driver'] != "") ? ucfirst(strtolower($config['driver'])) : "Native");

        $this->instance_name = "\\Session\\Drivers\\" . $driver . "Session";

        if (class_exists($this->instance_name))
        {
            $this->instance = new $this->instance_name($config);
        }
        else
        {
            throw new Exception("Session driver not found [".$this->instance_name."]", 1);
        }
    }

    public function get($key = null)
    {
        return $this->instance->get($key);
    }
    
    public function delete($key)
    {
        return $this->instance->delete($key);
    }
    
    public function set($key, $value)
    {
        return $this->instance->set($key, $value);
    }
    
    public function id()
    {
        return $this->instance->id();
    }
    
    public function regenerate($delete)
    {
        return $this->instance->regenerate($delete);
    }
    
    public function destroy()
    {
        return $this->instance->destroy();
    }

}