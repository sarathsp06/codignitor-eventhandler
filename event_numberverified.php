<?php
require_once 'event.php';
/**
* class : EventNumberVerified
*/
class EventNumberVerified extends Event {
	private $number = "";
	private $user_id = "";
	private $tenant_id = "";

	function __construct($event_name,array $details)
	{
	    $mandatory_fields = array('number','user_id', 'tenant_id');
		EventNumberVerified::validateDetails();
		parent::__construct($event_name,$details);
		foreach ($detail as $key => $value) {
			$this->set($key, $value);
		}
        
	}
}

?>