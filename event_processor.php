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
 * 3 . Delegates controll to the Listeners on events are triggered
 *
 *
 * @author Sarath S Pillai <sarath@exotel.in>
 */
class EventProcessor
{
    /**
   * $_listeners associative array of listeners
   * it contains the callback tuples
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
   * @access  public
   * @param string  The name of the event
   * @param array The callback for the Event
   * @return  void
   */
    public static function register($event, Listener &$listener)
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
            $this->register($listener['event'], $listener['listener']);
        }
    }

    /**
     *  Trigger
     *
     * Trigger the event
     * Call all the functions that are registered
     * Ping all  the webhooks that are registered
     * return array of funcion to status
     *          array(
     *          $listener->function => return value,..
     *          )
     *
     *
     * @access  public
     * @param Event   The event object corresponding to the event
     *                  It must have the properties as
     *
     * @param mixed Any data that is to be passed to the listener
     * @param string  The return type
     * @return mixed The return of the listeners, in the return type
     */
    public static function trigger(Event $event)
    {
        $calls = array();
        if (self::has_listeners($event->event_name, $listeners)) {
            foreach ($listeners as $listener) {
                try {
                  $calls[$listener->function] = $listener->act($event->event_details);
                } catch (Exception $e) {
                  $calls[$listener->function] = false;
                }
            }
        } else {
          //TODO:remove sarath
          echo "no listeners found " . json_encode($event);
        }
        return $calls;
    }

    /**
    * has_listeners Does the event have listeners or not
    * @access private
    * @param  string  $event The name of the event thats thrown
    * @return boolean        Whether the event have any listeners or not
    */
    private static function has_listeners($event, &$listeners)
    {
        if (isset(self::$_listeners[$event]) and count(self::$_listeners[$event]) > 0) {
            $listeners = self::$_listeners[$event];
            return true;
        }
        return false;
    }
}