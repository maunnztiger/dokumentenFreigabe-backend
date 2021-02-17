<?php
namespace schoolyard\Controller;

use schoolyard\Controller\ViewSetter;
use schoolyard\Application;

class CustomerController implements Viewsetter {
    protected $dataObj;
    private $session;

    public function __construct(){
        $this->session = Application::getModel('Session');
        $this->dataObj = $this->session->getSessionName('dataObj');
    }

    

    public function customerViewAction()
    {
        echo json_encode(['Menu: Right click here']);
       
    }
}