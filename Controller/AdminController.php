<?php
namespace dokumentenFreigabe\Controller;

use dokumentenFreigabe\Controller\ViewSetter;
use dokumentenFreigabe\Application;

class AdminController {
    protected $view;
    private $session;

    public function __construct(){
        $this->session = Application::getModel('Session');
    }

    public function adminViewAction()
    {
      if($_SERVER['REQUEST_METHOD'] == 'GET'){
        echo json_encode(['Menu: Right click here']);
      }
     
    

    }

    public function listUsersAction(){
        if($this->session->getSessionName('permission') == 'Admin' && 
        $_SERVER['REQUEST_METHOD'] == 'GET'){
            $invoker = Application::getModel('Invoker');
            $context = $invoker->getContext();
            $context->addParam('action', 'getUserList');
            $invoker->process();
            $this->session->setSessionName('userList', $context->get('userList'));
           
        }
        header('HTTP/1.0 200 OK');
        echo json_encode($this->session->getSessionName('userList')); 
       
        
    }

    public function getUserAction()
    {

        if (isset($_POST['name'])) {
            var_dump($_POST['name']);
            
           echo json_encode($this->session->setSessionName('user', (Application::getModel('Admin'))->getUser($_POST['name'])));
        }
       if($_SERVER['REQUEST_METHOD'] == 'GET'){
        header('HTTP/1.0 200 OK');
        echo json_encode(array($this->session->getSessionName('user')));
       }
           
        
       
            
    
    }

}