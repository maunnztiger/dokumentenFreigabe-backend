<?php
namespace schoolyard\Controller;

use schoolyard\Controller\ViewSetter;
use schoolyard\Application;

class IndexController implements ViewSetter
{
    protected $view;
    private $session;

    public function __construct(){
        $this->session = Application::getModel('Session');
    }

    public function setView(\schoolyard\Library\View $view)
    {
        $this->view = $view;
    }

   

    public function indexAction()
    {
        $this->view->setVars([
            'text' => ['Menu'],
        ]);
    }

    public function formularAction()
    {
        $this->view->setVars([
            'text' => 'Login',
        ]);
    }

    public function dispatchViewsAction()
    {

        if (isset($_POST['user']) && isset($_POST['password'])) {
           
            $invoker = Application::getModel('Invoker');
            $context = $invoker->getContext();
            $context->addParam('action', 'login');
            $context->addParam('username', $_POST['user']);
            $context->addParam('password', $_POST['password']);

            $invoker->process();
            $this->session->setSessionName('permission', $context->get('permission'));
            
            switch($this->session->getSessionName('permission')){    
                case 'Admin':
                    $context->addParam('action', 'dispatchAdminView');
                    $invoker->process();
                break;
                case 'Secretary':
                    $context->addParam('action', 'dispatchSecretaryView');
                    $invoker->process();
                break;
                case 'Teacher':
                    $context->addParam('action', 'dispatchTeacherView');
                    $invoker->process();
                break;
                default:  $this->view->setVars(['user' => 'No licence returned: ' . 'User: ' . $_POST['user'] . ',' . 'Password:' . $_POST['password']]);
                break;
            }
        }
    }

    public function logoutAction()
    {

        //$class = 'ISPComplaintsCRM\\Model\\Invoker';
        $invoker = Application::getModel('Invoker');
        $context = $invoker->getContext();
        $context->addParam('action', 'logout');
        $invoker->process();

        $this->view->setVars([
            'text' => $context->get('logout'),
        ]);
    }

    public function administrationOverviewAction()
    {
        $this->view->setVars([
            'text' => ['Beschwerde erstellen'],
        ]);
    }

}
