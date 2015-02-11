<?php
/**
 * Event class
 */
require_once 'event.php';
/**
 * Listener Class
 */
require_once 'listener.php';
/**
 * class EventNames
 */
require_once 'event_names.php';

/**
 * define the EVENT_PROCESSOR_PATH
 */
define('EVENT_PROCESSOR_PATH', dirname(__FILE__));

/**
 * Class  : EventProcessor
 *
 * The class that
 * 1 . Allows triggering of a particular event by passing any Object of
 *     class type Event or any subclass of the same
 * 2 . Allows to register for any event
 *     Enables to register function callbacks or
 *                                  webhooks
 *     2.1.callbacks = function specified is called with the details of the event as a parameter , an associative array
 *     2.2 webhooks = url will be called with a post request with the details of event as data , an associative array
 * 3.  Every listener must inherit frm Listener Class
 *
 *
 * @author Sarath S Pillai <sarath@exotel.in>
 */
class EventProcessor
{
    /**
	 * $_listeners associative array of listeners
	 * it contins the callback tuples
	 *           file_path, type , class , function would be array
	 *           file_path, type , webhook , method would be
	 *
	 * @var array
	 */
   protected static $_listeners = array();

   public function __construct()
   {
      $_listeners = $this->register_all_listeners();
   }

    /**
     * Call this method to get singleton
     *
     * @return EventProcessor
     */
    public static function instance()
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new EventProcessor();
        }

        return $inst;
    }

   /**
	 * Register
	 *
	 * Registers a Callback for a given event
	 *           a Webhook for a given event
	 * @access	public
	 * @param	string	The name of the event
	 * @param	array	The callback for the Event
	 * @return	void
	 */
    public static function register($event,Listener &$listener)
    {
        self::$_listeners[$event][] = $listener;
    }

    /**
     * register_all_listeners description
     * This function is called when the EventProcessor is instantiated
     * 1 . Read all the default listeners from the default listeners js
     * 2 . register all of them with the register function
     * @access private
     */
    private function register_all_listeners()
    {
        require_once "default_listeners.php";
        foreach ($default_listeners as &$listener) {
            $this->register($listener['event'],$listener['listener']);
        }
    }

   /**
	 * Trigger
	 *
	 * Trigger the event
	 * Call all the functions that are registered
	 * Ping all  the webhooks that are registered
	 * return array of funciojn to status
	 *          array(
	 *          $listener->function => return value,..
	 *          )
	 *
	 *
	 * @access	public
	 * @param	Event   The event object corresponding to the event
	 *                  It must have the properties as
	 *
	 * @param	mixed	Any data that is to be passed to the listener
	 * @param	string	The return type
	 * @return	mixed	The return of the listeners, in the return type
	 */
    public static function trigger(Event $event)
    {
        $calls = array();
        if (self::has_listeners($event->name, $listeners)) {
            foreach ($listeners as $listener) {
                try {
                    switch ($listener->type) {
                        case ListenersTypes::Callable:
                            if (!empty($listener->class)) {
                                require_once "$listener->file";
                                $calls[$listener->function] = call_user_func_array(array($listener->class, $listener->function), $event->details);
                            } else {
                                require_once "$listener->file";
                                $calls[$listener->function] = call_user_func_array($listener->function, $event->details);;
                            }
                            break;
                        case ListenersTypes::WebHook:
                            $calls[$listener->function] = EventProcessor::pingWebHook($listener->function, $event->details);
                            break;
                        default:
                            $calls[$listener->function] = false;
                            break;
                    }
                } catch (Exception $e) {
                    $calls[$listener->function] = false;
                }
            }
        }
        return $calls;
    }

    /**
	  * has_listeners Does the event have listeners or not
    * @access private
	  * @param  string  $event The name of the event thats thrown
	  * @return boolean        Whether the event have any listeners or not 
	  */
    private static function has_listeners($event,&$listeners)
    {
        if (isset(self::$_listeners[$event]) and count(self::$_listeners[$event]) > 0) {
            $listeners = self::$_listeners[$event];

            return true;
        }

        return false;
    }

   /**
    * pingWebHook description
    * @param  string $url ping the url
    * @param  array  $data The data to be sent as post reqest to the url
    * @return array  an array of listener function names and
    */
    private static function pingWebHook($url = 'null',array $data)
    {
        if ($url == 'null') {
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
      $fp = fsockopen($parts['host'],80,$errno,$errstr,30);
      if (!$fp) {
          return "Unable to opensocket";
      }
      $out = "POST ".$parts['path']." HTTP/1.1\r\n";
      $out.= "Host: ".$parts['host']."\r\n";
      $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
      $out.= "Content-Length: ".strlen($post_string)."\r\n";
      $out.= "Connection: Close\r\n\r\n";
      $out.= $post_string;
      fwrite($fp, $out);
      fclose($fp);
      return ($errno == 0) ? true : false;
    }
}