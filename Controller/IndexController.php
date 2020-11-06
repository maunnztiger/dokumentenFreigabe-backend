<?php
namespace ISPComplaintsCRM\Controller;

use ISPComplaintsCRM\Controller\ViewSetter;
use ISPComplaintsCRM\Model\Model;


class IndexController implements ViewSetter
{
    protected $view;

    public function setView(\ISPComplaintsCRM\Library\View $view)
    {
        $this->view = $view;
    }

    public function serialize()
    {
        return serialize(array(
            'parentData' => parent::serialize(),
        ));
    }

    public function indexAction()
    {
        $this->view->setVars([
            'text' => 'Login',
        ]);
    }

    public function formularAction(){
        $this->view->setVars([
            'text' => 'Login',
        ]);
    }

    public function loginAction(){
        
        if(null !== $_POST['user'] && null !== $_POST['password']){
            $class = 'ISPComplaintsCRM\\Model\\Invoker';
            $invoker = new $class;
            $context = $invoker->getContext();
            $context->addParam('action', 'login');
            $context->addParam('username', $_POST['user']);
            $context->addParam('password', $_POST['password']);
            
            $invoker->process();
            $this->view->setVars([
                'text' => $context->get('permission'),
            ]);
        }
        
        
       
    }


}