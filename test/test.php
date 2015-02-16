<?php
/**
 * Class : EventHandler , Event, Listener and EventNames
 */

require_once EVENT_PROCESSOR_PATH.'/event_processor.php';

class eventsTest
{
    public function thenga1()
    {
        return "thenga1-success";
    }
}

function thenga2()
{
    return 'thenga2-success';
}

//===================JUST CHECKING IF VERY BASIC THING IS WORKING =======================
//echo json_encode(EventHandler::instance()->trigger(new Event(EventNames::NUMBER_VERIFIED,array('number'=>'8907965331'))));
//echo CODEBASEPATH."/OpenVBX/controllers/exoapi.php\n";
