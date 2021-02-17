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
}