<?php

/**
 * Class : Listener
 * This is the base class for Listeners
 * @author Sarath S Pillai <sarath@exotel.in>
 *
 */
class Listener
{
    public $class = null;
    public $function = null;
    public $type = "";

    /**
     * __construct
     * @param string $file     The full path to the listener
     * @param string $class    The class name to which the function belongs to if member function
     *                         null if the function is not a class member function
     * @param string $function The function name
     * @param int    $type     The type of the Listener as given by ListenersTypes class
     */
    public function __construct($file, $class, $function, $type)
    {
      if (empty($function) || !isset($type)) {
        throw new Exception("function or type is empty", 1);
      }

      if (empty($file) && $type == ListenersTypes::Callable) {
        throw new Exception("file name is empty for a callable type", 1);

      }
      $this->file = $file;
      $this->class = $class;
      $this->function = $function;
      $this->type = $type;
    }

    /**
     * set description
     * @param string $key  To set a key
     * @param mixed  $vale value
     */
    public function set($key, $value)
    {
       if (property_exists($self, $key)) {
           $this->{$key} = $name;
       }

       return $this;
    }

    /**
     * get description
     * @param  string $key The property of the class
     * @return mixed  Value for the key if any or null
     */
    public function get($key, $name)
    {
        if (property_exists($self, $key)) {
           return $this->{$key};
       } else {
           return null;
       }
    }

    /**
     * act
     * Listening is a positive act but you have to put yourself out to do it.
     *
     * This fucntion is the one that does the job described by the listener
     * This is intended to be called by the event_processor upon getting a trigger on an event
     * that this object registered for
     *
     * The type of listeners could be
     *    callbacks = function specified is called with the details of the event as a parameter , an associative array
     *    webhooks = url will be called with a post request with the details of event as data , an associative array
     *
     * @param  array $details the details of the event as a associative array
     * @return mixed returned value from the function or on any exeptions false
     */
    final public function act(array $details)
    {
        $result = false;
        try {
            switch ($this->type) {
                case ListenersTypes::Callable:
                    if (!empty($this->class)) {
                        require_once "$this->file";
                        $result = call_user_func_array(array($this->class, $this->function), $details);
                    } else {
                        require_once "$this->file";
                        $result = call_user_func_array($this->function, $details);;
                    }
                    break;
                case ListenersTypes::WebHook:
                   $result = self::pingWebHook($this->function, $details);
                    break;
                default:
                    $result = false;
                    break;
            }
        } catch (Exception $e) {
            $result = json_encode(array('success' => false, 'reason' => $e->getMessage()));
        }
        return $result;
    }

   /**
    * pingWebHook description
    * This makes an asynchronous call to the url specified with the $data as data parameters anf
    * do not wait for the status reply to come 
    * @param  string $url  ping the url
    * @param  array  $data The data to be sent as post reqest to the url
    * @return boolean      
    */
    private static function pingWebHook($url = null, array $data)
    {
        if (empty($url)) {
          return false;
        }
        $post_params = array();
        $errno = 0;
        $errstr = ";";
        foreach ($data as $key => &$val) {
            $post_params[] = $key.'='.urlencode($val);
        }
        $post_string = implode('&', $post_params);
        $parts=parse_url($url);
        $fp = fsockopen($parts['host'], 80, $errno, $errstr, 30);
        if (!$fp) {
            return "false";
        }
        $out = "POST ".$parts['path']." HTTP/1.1\r\n";
        $out.= "Host: ".$parts['host']."\r\n";
        $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
        $out.= "Content-Length: ".strlen($post_string)."\r\n";
        $out.= "Connection: Close\r\n\r\n";
        $out.= $post_string;
        if (false === fwrite($fp, $out)){
            return false;
        }
        fclose($fp);
        return true ;
    }
}

/**
 * class ListenersTypes
 *
 *Types of listeners
 */
abstract class ListenersTypes
{
    /**
     * The Listener type used for regisrtering webhooks
     * @var int WebHook
     */
    const WebHook = 0;
    /**
     * The listener type used for registering functions
     * @var  int Callable
     */
    const Callable = 1;
}