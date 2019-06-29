# atk4-fastroute
WIP integration with FastRoute

REALLY EXPERIMENTAL
-------------------

Define routes :
 - RoutedCallable : as Callable
 - RoutedUI : as atk4/ui/* Class to be added to the App
 - RoutedMethod : as Class Method to be called like a controller
 
RoutedUI & RoutedMethod can be serialized as Array, and can be loaded via ConfigTrait ( not implemented yet)

Using Interface implementation to define behaviour and needs of the class.

iBeforeRoutable => request method OnBeforeRoute which will be called right before OnRoute ( setting up app? )
iAfterRoutable => request method OnAfterRoute which will be called right after OnRoute ( setup other elements? )
iArrayable => have fromArray and toArray to be serializeable
iNeedAppRun => if is needed to call ->app->run() after Routing method calls

A few more things
-----------------
- Having a collection of routes, that in the future can be translatable  