<?php
/**
 * Class : Event
 * Event Base Class
 * All the events inherit from this class
 *
 * @author Sarath S Pillai <sarath@exotel.in>
 *
 */

class Event
{
    /**
     * This property should be treatened as "readonly". If you change this value
     * something scary will happen to you .
     *
     * @readonly
     */
    public $name = "";
    public $timestamp = "";
    public $source = "";
    public $details = array();

    protected static $mandatory_fields = array();
    protected static $__CLASS__ = __CLASS__;
    public function __construct($event_name,array $details)
    {
        $this->name = $event_name;
        $this->timestamp = time();
        $this->details =  $details;
    }

    /**
     * set description
     * @param string $key  To set a key
     * @param mixed  $vale value
     */
    public function set($key, $value)
    {
       if (property_exists(self::$__CLASS__, $key)) {
           $this->{$key} = $value;
       }

       return $this;
    }

    /**
     * set description
     * @param string $key   The property of the class
     * @param mixed  $value Value to be assigned to the property
     */
    public function get(string $key)
    {
        if (property_exists(self::$__CLASS__, $key)) {
           return $this->{$key};
       } else {
           return null;
       }
    }

    /**
     * validateDetails description
     * @param  array   $details
     * @return boolean if  the array have all the required fields
     */
    final protected static function validateDetails(array $details)
    {
        //If $details is
        //empty,non associative array,not a array return false
        if (empty($details) ||
            array_keys($details) == range(0, count($details) - 1) ||
            !is_array($details)
            ) {
            throw new Exception("$details given cant be validated as source details", 1);

        }
        $details_fields = array_keys($details);
        $missing_fields = array_diff(self::$mandatory_fields, $details_fields);
        if ($missing_fields == array()) {
            return true;
        } else {
            throw new Exception('Mandatory fields missing ::' .implode(', ',$missing_fields) , 1);

        }
    }
}
