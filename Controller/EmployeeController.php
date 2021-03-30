<?php
namespace schoolyard\Controller;

use schoolyard\Controller\ViewSetter;
use schoolyard\Application;

class EmployeeController implements Viewsetter {
   
    private $session;
    private $dataObj;

    public function __construct(){
        $this->session = Application::getModel('Session');
        $this->dataObj = $this->session->getSessionName('dataObj');
    }

  

    public function employeeViewAction(){
        echo json_encode(['Menu: Right click here']);
    }


    public function getPDFFileNamesAction(){
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $invoker = Application::getModel('Invoker');
            $context = $invoker->getContext();
            $context->addParam('action', 'getPDFFileNames');
            $invoker->process();

            $fileNames = $context->get('fileNames');
            echo json_encode($fileNames);
        }
    }

    public function playVideoAction(){
        if($_SERVER['REQUEST_METHOD'] === 'POST' && $this->dataObj->get('permission') == 'Employee'){
            $this->dataObj->setParam('videoName', $_POST['name']);
        }      
                
        if($_SERVER['REQUEST_METHOD'] === 'GET' && $this->dataObj->get('videoName') === "BlackbookSessions" && $this->dataObj->get('permission') == 'Admin'){
            $invoker = Application::getModel('Invoker');
            $context = $invoker->getContext();
            $context->addParam('action', 'playBlackbookVideo');
            $invoker->process();
        }        
              
        if($_SERVER['REQUEST_METHOD'] === 'GET' && $this->dataObj->get('videoName') === "Detroit"){
            $invoker = Application::getModel('Invoker');
            $context = $invoker->getContext();
            $context->addParam('action', 'playDetroitVideo');
            $invoker->process();
        }   
    }
}