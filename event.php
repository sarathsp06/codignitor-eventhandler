<?php
/**
 * Class : Event
 * Event Base Class
 * All the events inherit from this class
 *
 * @author Sarath S Pillai <sarath@exotel.in>
 */


class Event
{
    /**
     * This properties should be treated as "readonly". If you change this value
     * something scary will happen to you .
     *
     * @readonly
     */
    public $event_name = "";
    public $event_timestamp = "";
    public $event_source = "";
    public $event_details = array();

    protected static $mandatory_fields = array();
    protected static $__CLASS__ = __CLASS__;
    public function __construct($event_name, array $details)
    {
        $this->event_name = $event_name;
        $this->event_timestamp = time();
        //sorting the details in the order in which the $mandatory_fields is defined
        $this->event_details =  $this->array_compared_sort($details, self::$mandatory_fields);
    }

    /**
     * set description
     * @param string $key    The property name  of the class to be set
     * @param mixed  $value  value for the property
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
        //empty,non associative array or not a array throw exception
        if (empty($details) ||
            array_keys($details) == range(0, count($details) - 1) ||
            !is_array($details)
            ) {
            throw new Exception(var_export($details) . "given cant be validated as source details", 1);
        }
        $details_fields = array_keys($details);
        $missing_fields = array_diff(self::$mandatory_fields, $details_fields);
        if ($missing_fields == array()) {
            return true;
        } else {
            throw new Exception('Mandatory fields missing ::' .implode(', ', $missing_fields), 1);
        }
    }

    /**
     * array_compared_sort
     * function sorts associative array arr1 with respect to arr2 values
     * This is what it does
     *      arr1 = ['number','user_id', 'tenant_id']
     *      arr2 = {"blah":"blah", "user_id":"13","tenant_id":"123", "number":"8907965331"}
     *
     * The result will be
     *     {"number":"8907965331","user_id":"13","tenant_id":"123","blah":"blah"}
     * The relative index of the keys which are not in the arr1 will also be maintained
     *
     * @param  array &$arr1 The associative array to be sorted passed by reference
     * @param  array $arr2  The array for lookup
     * @return array The sorted array
     */
    private function array_compared_sort(array &$arr1, array $arr2)
    {
        $len_arr2 = count($arr2);
        $dict_arr2 = array_flip($arr2);
        $dict_arr1 = array_flip(array_keys($arr1));
        uksort($arr1, function ($a, $b) use ($dict_arr2, $dict_arr1, $len_arr2) {
            if ($a == $b) {
                return 0;
            }
            $a_val = isset($dict_arr2[$a]) ? $dict_arr2[$a] : $len_arr2 + $dict_arr1[$a];
            $b_val = isset($dict_arr2[$b]) ? $dict_arr2[$b] : $len_arr2 + $dict_arr1[$b];

            return $a_val < $b_val ? -1 : 1;
        });
        return $arr1;
    }
}