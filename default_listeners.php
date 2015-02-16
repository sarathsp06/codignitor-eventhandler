    <?php
/**
 * This is the container listing all the default event listeners
 * How to add a event listener
 * =============================
 * Add an entry in the $default_listeners array
 * The entry should be in the format :
 *     array("event":"The name of the event","listener":"Listener instance")
 *     a Listener instance is created as
 *         new Listener("full filepath of where the function is defined",
 *                      "class name if its a member function")
 */

/**
 * class EventNames
 */
require_once 'event_names.php';

/**
 * class Listener and ListenerTypes
 */
require_once 'listener.php';

/**
 * $default_listeners
 * Put here all the default listeners
 * @var array
 */

$default_listeners = array (
    array(
        "event"=> EventNames::NUMBER_VERIFIED,
        "listener" => new Listener(
            EVENT_PROCESSOR_PATH."/test/test.php",
            'eventsTest',
            'thenga1',
            ListenersTypes::Callable)),
    array(
        "event"=> EventNames::NUMBER_VERIFIED,
        "listener" => new Listener(
            EVENT_PROCESSOR_PATH."/test/test.php",
            null,
            'thenga2',
            ListenersTypes::Callable)),
    array(
        "event"=> EventNames::NUMBER_VERIFIED,
        "listener" => new Listener(
            EVENT_PROCESSOR_PATH."/test/test.php",
            null,
            'http://obelix.exotel.in/exoapi/getqbransactions/1',
            ListenersTypes::WebHook)),
    array(
        "event"=> EventNames::NUMBER_VERIFIED,
        "listener" => new Listener(
            CODEBASEPATH."/OpenVBX/controllers/exoapi.php",
            'ExoApi',
            'exotel_test_event',
            ListenersTypes::Callable))

);
