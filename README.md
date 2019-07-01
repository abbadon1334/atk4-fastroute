# atk4-fastroute
WIP integration with FastRoute

Still needs :

- [ ] more test for patterns route
    - [ ] test for calls with extraparameters
- [ ] check if can be simplified
- [ ] add Translation as option
    - [ ] add slugging of routes
- [ ] check if can be added MiddlewareInterface
    - [ ] to be executed by pattern (Another Router in Router that dispatch MW by route match) ?
    - [ ] to be executed per route (before handlingRoute)?
- [ ] add comments to public methods!!!
- [ ] add documentation

EXPERIMENTAL
-------------------

Define routes :
 - RoutedCallable : as Callable
 - RoutedUI : as atk4/ui/* Class to be added to the App
 - RoutedMethod : as Class Method to be called like a controller
 
Routes can be serialized as array to allow loading from external file. 

Using Interface implementation to define behaviour and needs of the class ( like flags ) :

 - iBeforeRoutable : request method OnBeforeRoute which will be called right before OnRoute ( setting up app? )
 - iAfterRoutable : request method OnAfterRoute which will be called right after OnRoute ( setup other elements? )
 - iArrayable : have fromArray and toArray to be serializeable
 - iNeedAppRun : if is needed to call ->app->run() after Routing method calls

Next step
-----------------
- Having a collection of routes, that in the future can be translatable  