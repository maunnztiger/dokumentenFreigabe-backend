<?php
namespace schoolyard\Controller;

use schoolyard\Controller\ViewSetter;
use schoolyard\Application;

class AdminController implements Viewsetter {
    protected $view;
    private $session;

    public function __construct(){
        $this->session = Application::getModel('Session');
    }

    public function setView(\schoolyard\Library\View $view)
    {
        $this->view = $view;
    }

    public function adminViewAction()
    {
        $this->view->setVars([
            'text' => ['Menu: Right click here'],
        ]);

    }

    public function listUsersAction(){
        if($this->session->getSessionName('permission') == 'Admin'){
            $invoker = Application::getModel('Invoker');
            $context = $invoker->getContext();
            $context->addParam('action', 'getUserList');
            $invoker->process();
            $this->session->setSessionName('userList', $context->get('userList'));
        }

        $this->view->setVars([
            'userList' => $this->session->getSessionName('userList')
            ]);
    }



}