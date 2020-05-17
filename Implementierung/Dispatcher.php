<?php

class Dispatcher
{

    private $request;

    public function dispatch()
    {
        $this->request = new Request();
        $controller = $this->getController();
        if ($controller == null) {
            return_404_error();
        } else {
            if(method_exists($controller,$this->request->action)) {
                call_user_func_array([$controller, $this->request->action], array($this->request->parameter));
            }else{
                return_404_error();
            }
        }
    }

    private function getController()
    {
        $controller_class = ucfirst($this->request->controller_name) . 'Controller';
        $controller_file = SERVER_ROOT . 'Controller/' . $controller_class . '.php';
        if (file_exists($controller_file)) {
            require($controller_file);
            return new $controller_class($this->request->method);
        }
        return null;
    }


}

?>