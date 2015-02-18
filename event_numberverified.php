<?php
require_once CODEBASEPATH.'/Events/event.php';
require_once CODEBASEPATH.'/Events/event_names.php';

/**
* class : EventNumberVerified
*/
class EventNumberVerified extends Event
{
    private $number = "";
    private $user_id = "";
    private $tenant_id = "";
    private static $name = EventNames::NUMBER_VERIFIED;

    public function __construct(array $details)
    {
        $__CLASS__ = __CLASS__;
        self::$mandatory_fields = array('value', 'user_id', 'tenant_id');
        EventNumberVerified::validateDetails($details);
        parent::__construct(self::$name, $details);
        foreach ($details as $key => $value) {
            $this->set($key, $value);
        }
    }
}
