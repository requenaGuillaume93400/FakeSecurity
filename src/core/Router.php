<?php

namespace Core;

class Router {
    
    // Three dimensionnal array that contains  PageName[action][controllerName(index 0)] & PageName[action][controllerMethod(index 1)]
    private const ROUTE = [
                            'homepage' => [
                                'action' =>['Controllers\Homepage' , 'run']
                                ],
                            'account' => [
                                'action'=>['Controllers\Account' , 'run']
                                ],
                            'backoffice' => [
                                'action' => ['Controllers\BackOffice', 'run']
                                ],
                            'connexion' => [
                                'action' => ['Controllers\Connexion', 'run']
                                ],
                            'deconnexion' => [
                                'action' => ['Controllers\Deconnexion', 'run']
                                ],
                            'demand' => [
                                'action' => ['Controllers\Demand', 'run']
                                ],
                            'modify' => [
                                'action' => ['Controllers\Modify', 'run']
                                ],
                            'order' => [
                                'action' => ['Controllers\Order', 'run']
                                ],
                            'services' => [
                                'action' => ['Controllers\Services', 'run']
                                ],
                            'subscribe' => [
                                'action' => ['Controllers\Subscribe', 'run']
                                ],
                            'update' => [
                                'action' => ['Controllers\Update', 'run']
                            ],
                            'sanction' => [
                                'action' => ['Controllers\Sanction', 'run']
                            ],
                            'bonus' => [
                                'action' => ['Controllers\Bonus', 'run']
                            ],
                            'rules' => [
                                'action' => ['Controllers\Rules', 'run']
                                ],
                            'quality' => [
                                'action' => ['Controllers\Quality', 'run']
                                ]
                          ];


    // Choose which page to run, @return void
    public function route():void 
    {
        // Set default page
        $page = 'homepage';

        // If we have a value in parameter page, and if this value exist in our array ROUTE
        if(isset($_GET['page']) && isset(self::ROUTE[$_GET['page']])) {
            $page = $_GET['page'];
        }

        // Choose the controller of the requested page (will give Controller\Account for exemple)
        $controllerName = self::ROUTE[$page]['action'][0];

        // If controller is BackOffice, i need to call four Models class cause i used composition
        if($controllerName === 'Controllers\BackOffice'){

            $controller = new $controllerName(new \Models\Client, new \Models\Agent, new \Models\Order, new \Models\Sanction);

        }else{
            // Else instanciate the class from the choosen controller
            $controller = new $controllerName();
        }
        
        // Get the method name
        $method = self::ROUTE[$page]['action'][1];

        // Call the $instance->method()
        $controller->$method();       
    }

}