<?php
namespace schoolyard\Controller;

use schoolyard\Controller\ViewSetter;
use schoolyard\Application;

class CustomerController implements Viewsetter {
    protected $view;
    private $session;

    public function __construct(){
        $this->session = Application::getModel('Session');
    }

    public function setView(\schoolyard\Library\View $view)
    {
        $this->view = $view;
    }

    public function customerViewAction()
    {
        $this->view->setVars([
            'text' => ['Menu: Right click here'],
        ]);

    }
}