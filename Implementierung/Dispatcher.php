<?php

/**
 * Class Dispatcher
 * Gets action and parameter from request and calls correct controller-function
 */
class Dispatcher
{

    private $request;

    public function dispatch()
    {
        //get data from request
        $this->request = new Request();
        $controller = $this->loadController();
        if ($controller == null) {
            return_404_error();
        } else {
            //call controller-function with parameter
            if(method_exists($controller,$this->request->getAction())) {
                call_user_func_array([$controller, $this->request->getAction()], array($this->request->getParameter()));
            }else{
                return_404_error();
            }
        }
    }

    /**
     * Loads Controller based ond controller-name
     * @return Controller/null
     */
    private function loadController()
    {
        $controllerClass = ucfirst($this->request->getControllerName()) . 'Controller';
        $controllerFile = SERVER_ROOT . 'Controller/' . $controllerClass . '.php';
        if (file_exists($controllerFile)) {
            require($controllerFile);
            return new $controllerClass($this->request->getMethod());
        }
        return null;
    }


}

?>