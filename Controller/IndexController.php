<?php
namespace dokumentenFreigabe\Controller;

use dokumentenFreigabe\Controller\ViewSetter;
use dokumentenFreigabe\Application;
use dokumentenFreigabe\Model\Registry;

class IndexController 
{
 
    private     $session;
    private     $dataObj; 

    public function __construct(){
        $this->session = Application::getModel('Session');
    }

    public function indexAction()
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            echo json_encode(['Menu']);
        }
    }

    public function formularAction()
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            echo json_encode(['Login']);
        }
    }

    public function dispatchViewsAction()
    {

        if (isset($_POST['user']) && isset($_POST['password']) &&
            $_SERVER['REQUEST_METHOD'] == 'POST') {
          
            $invoker = Application::getModel('Invoker');
            $context = $invoker->getContext();
            $context->addParam('action', 'login');
            $context->addParam('username', $_POST['user']);
            $context->addParam('password', $_POST['password']);
            $invoker->process();
            
            $this->dataObj = Application::getModel('Registry');
            $this->session->setSessionName('dataObj', $this->dataObj);
            $this->dataObj->setParam('permission', $context->get('permission'));
            echo json_encode(strtolower($context->get('permission')));
        }
    }

    public function logoutAction()
    {
        $invoker = Application::getModel('Invoker');
        $context = $invoker->getContext();
        $context->addParam('action', 'logout');
        $invoker->process();

       
    }

   

}
