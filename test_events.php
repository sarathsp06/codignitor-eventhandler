<?php
/**
 * Class : EventHandler , Event, Listener and EventNames
 */
require_once 'events_handler.php';

class eventsTest
{
    public static function setUp()
    {
        EventHandler::register(EventNames::NUMBER_VARIFIED, new Listener(__FILE__, __CLASS__,'thenga1',ListenersTypes::Callable));
        EventHandler::register(EventNames::NUMBER_VARIFIED, new Listener(__FILE__, null,'thenga2',ListenersTypes::Callable));
        EventHandler::register(EventNames::NUMBER_VARIFIED, new Listener(__FILE__, null,'http://httpbin.org/ip',ListenersTypes::WebHook));
    }

    public function thenga1()
    {
        echo 'thea 1'.PHP_EOL;

        return true;
    }
}

function thenga2()
{
    echo 'thenga2'.PHP_EOL;

    return true;
}



//===================JUST CHECKING IF VERY BASIC THING IS WORKING =======================

echo 'Trying to do setup';
eventsTest::setUp();

echo 'Setup done';
echo json_encode(EventHandler::trigger(new Event(EventNames::NUMBER_VARIFIED,array('number'=>'8907965331'))));
