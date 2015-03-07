### Codegnitor EventHandler
Here its an event handler library to make the codeignitor [MVC] framework to work with event based systems also.The system can be used with any php project but s tested agains codeigntor only 
How to use 

```
git clone https://github.com/sarathsp06/codignitor-eventhandler.git
```

### Triggering Events
You can either trigger custom triggers or predefined triggers

```
EventHandler::instance()->trigger(new Event(EventNames::NUMBER_VERIFIED,array('number'=>'8907965331')))
```
Here new trigger named EventNames::NUMBER_VERIFIED is triggered it could be any string
Passed the array as the arguments required for the listeners here 'number'
Listeners can be added in the default_listeners file as 
### Adding Listeners
```
#array("event":"The name of the event","listener":"Listener instance")
$default_lsteners[] = array(
    "event"=> EventNames::NUMBER_VERIFIED,
    "listener" => new Listener(
    CODEBASEPATH."/some_helper_fle.php",   //file path in which the function deines or null if webhook
    null,                                  //class name if member function else null
    'verifyAccount',                       //function name or url
    ListenersTypes::Callable));            //type supported types are ListenersTypes::Callable and WebHook
```
### Authors and Contributors
In 2015, Sarath S Pllai (@sarathsp06) started and currently is not been maintained but any bug reports wpuld be addressed 

### Support or Contact
Having trouble using the library contact me at sarath.sp06@gmail.com

