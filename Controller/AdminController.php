<?php
namespace dokumentenFreigabe\Controller;

use dokumentenFreigabe\Controller\ViewSetter;
use dokumentenFreigabe\Application;

class AdminController {
    protected $view;
    private $session;
    private $dataObj;

    public function __construct(){
        $this->session = Application::getModel('Session');
        $this->dataObj = $this->session->getSessionName('dataObj');
    }

    public function adminViewAction()
    {
      if($_SERVER['REQUEST_METHOD'] == 'GET'){
        echo json_encode(['Menu: Right click here']);
      }
     
    

    }

    public function listUsersAction(){
       
        
        if( $this->dataObj->get('permission') == 'Admin' && 
            $_SERVER['REQUEST_METHOD'] == 'GET'){
            $invoker = Application::getModel('Invoker');
            $context = $invoker->getContext();
            $context->addParam('action', 'getUserList');
            $invoker->process();
            echo json_encode($context->get('userList')); 
        }
       
    }

    public function getUserAction()
    {

        if (isset($_POST['name']) && $this->dataObj->get('permission') == 'Admin') {
           
            echo json_encode($this->session->setSessionName('user', (Application::getModel('Admin'))->getUser($_POST['name'])));
        }
       if($_SERVER['REQUEST_METHOD'] === 'GET'){
        header('HTTP/1.0 200 OK');
        echo json_encode(array($this->session->getSessionName('user')));
       }
           
    }

    public function updateUserAction()
    {

        if( $_SERVER['REQUEST_METHOD'] === 'PUT' && $this->dataObj->get('permission') == 'Admin') {
            
            parse_str(file_get_contents('php://input'), $_PUT);
            $invoker = Application::getModel('Invoker');
            $context = $invoker->getContext();
            $context->addParam('action', 'updateUser');
            $context->addParam('group', $_PUT['groupName']);
            $context->addParam('user-id', $_PUT['user-id']);
            $context->addParam('department', $_PUT['department']);
            $invoker->process();
        
            $this->dataObj->setParam('updatedUser', $context->get('updatedUser'));
       
            }
            header('HTTP/1.0 200 OK');
            echo json_encode($this->dataObj->get('updatedUser'));
       
    }

    public function deleteUserAction()
    {

        if( $_SERVER['REQUEST_METHOD'] === 'DELETE' && $this->dataObj->get('permission') == 'Admin') {
            
            $invoker = Application::getModel('Invoker');
            $context = $invoker->getContext();
            $context->addParam('action', 'deleteUser');
            $context->addParam('userName', $_GET['name']);
            $invoker->process();
            header('HTTP/1.0 200 OK');
        }
       
    }

}