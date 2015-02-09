<?php

/**
 * Class : Listener
 * This is the base class for any Listeners to be used for when 
 * listening to a event
 * 
 *
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
      public function __construct($file,$class,$function,$type)
    {
      if (empty($file) || empty($function) || !isset($type) ) {
        throw new Exception("file ,function or type is empty", 1);
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
    public function set($key,$value)
    {
       if (property_exists($self, $key)) {
           $this->{$key} = $name;
       }

       return $this;
    }

    /**
     * set description
     * @param string $key   The property of the class
     * @param mixed  $value Value to be assigned to the property
     */
    public function get($key,$name)
    {
        if (property_exists($self, $key)) {
           return $this->{$key};
       } else {
           return null;
       }
    }
}

/**
 * class ListenersTypes
 * 
 *Types of listeners
 */
abstract class ListenersTypes
{
    const WebHook = 0;
    const Callable = 1;
}
