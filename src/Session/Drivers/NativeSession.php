<?php
namespace Session\Drivers;

class NativeSession implements \Session\SessionInterface
{
    private $_id = null;
    private $_agent = null;
    private $app_name = "";

    public function __construct(array $config = array())
    {

        if (in_array('sha512', hash_algos()))
        {
            ini_set('session.hash_function', 'sha512');
        }

        ini_set('session.hash_bits_per_character', 5);
        ini_set('session.use_only_cookies', 1);

        $cookieParams = session_get_cookie_params(); 
        session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], false, true); 

        if (!headers_sent()){
            session_start();
        }else{
            throw new \Exception("Header already sent", 1);
        }

        $this->_id = session_id();
        $this->_agent = md5($_SERVER['HTTP_USER_AGENT']);
        $this->app_name = ( isset($config['app_name']) ? $config['app_name'] : "" );

        if (!$this->_agent)
        {
            if ($this->_agent != md5($_SERVER['HTTP_USER_AGENT']))
            {
                throw new \Exception("HTTP_USER_AGENT problem", 1);
                
            }
        }
        else
        {
            $this->_agent = md5($_SERVER['HTTP_USER_AGENT']);
        }
    }
    
    public function get($key = null)
    {
        if ($key)
            return ( isset($_SESSION[$this->app_name . "." . $key]) ? $_SESSION[$this->app_name . "." . $key] : null );
        else
            return $_SESSION;
    }
    
    public function set($key, $value)
    {
        return $_SESSION[$this->app_name . "." . $key] = $value;
    }
    
    public function delete($key)
    {
        if (isset($_SESSION[$this->app_name . "." . $key]))
        {
            $_SESSION[$this->app_name . "." . $key] = null;
            unset($_SESSION[$this->app_name . "." . $key]);
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function id()
    {
        return $this->_id;
    }
    
    public function regenerate($delete)
    {
        session_regenerate_id($delete);
        $this->_id = session_id();
        return $this->_id;
    }
    
    public function destroy()
    {
        $_SESSION = array();
        $this->_id = null;
        session_destroy();
        return true;
    }

}