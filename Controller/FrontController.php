<?php

namespace dokumentenFreigabe\Controller;

use dokumentenFreigabe\Controller\FrontControllerInterface;
use dokumentenFreigabe\Library\View;
use dokumentenFreigabe\Library\NotFoundException;
use dokumentenFreigabe\Application;
use ReflectionClass;


class FrontController implements FrontControllerInterface{

    protected $controller;
    protected $action;                    
    protected $permission;

    public function __construct($options = array()){
        if(empty($options)){
            $this->parseUri();
        } else {
            
            if (isset($options["controller"])) {
                $this->setController($options["controller"]);
            }
            if (isset($options["action"])) {
                $this->setAction($options["action"]);     
            }
        }
    }

    public function parseUri(){
        $url = (isset($_GET['_url']) ? $_GET['_url'] : '');
        //var_dump($_GET['_url']);
        $urlparts = explode('/', $url);

        $controllerName = (isset($urlparts[0]) && $urlparts[0] ? $urlparts[0] : 'index');
        $actionName = (isset($urlparts[1]) && $urlparts[1] ? $urlparts[1] : 'index');

        if(isset($controllerName)){
            $this->setController($controllerName);
        }

        if(isset($actionName) && isset($controllerName)){
            $this->setAction($actionName);
           
        }
    }

    public function setController($controller) {
        
        $this->controller = $controller;

    }

    public function setAction($action) {    
        $actionMethodName = $action. "Action";
        $controllerClassName = '\\dokumentenFreigabe\\Controller\\'.ucfirst(strtolower($this->controller))."Controller";
        $reflector = new ReflectionClass($controllerClassName);
        if (!$reflector->hasMethod($actionMethodName)) {
            throw new \dokumentenFreigabe\Library\NotFoundException(
                "The controller action '$action' has been not defined in '$controllerClassName'");
        }
        $this->action = $actionMethodName;
    }

   

    public function run(){
    try{
        
       if(!isset($_SESSION)){
           session_start();
       }
       
       
        if(isset($_SESSION['dataObj'])){
            $dataObj = $_SESSION['dataObj'] ;
            $this->permission = $dataObj->get('permission');
        }
 
           
        
 
       
        if((!isset($this->permission) && isset($this->permission) !== 'Admin' && $this->controller == 'admin') ||
          (!isset($this->permission) && isset($this->permission) !== 'Employee' && $this->controller == 'employee')  || 
          (!isset($this->permission) && isset($this->permission) !== 'Customer' && $this->controller == 'customer') ) {
            http_response_code(403);
            echo 'No Permission to access this View';
        } else {
            $controllerName = ucfirst($this->controller.'Controller');
            $controller = Application::getController($controllerName);
            $actionMethodName = $this->action;
            $controller->$actionMethodName();
            //echo ini_get('post_max_size');
       
            
        }
    } catch (\dokumentenFreigabe\Library\NotFoundException $e) {
        http_response_code(404);
        echo 'Page not found: ' . $controllerName. '::' . $actionMethodName;
    } catch (\Exception $e) {
        http_response_code(500);
        echo 'Exception: ' . $e->getMessage() . ' ' . $e->getTraceAsString();
    }
    }
}