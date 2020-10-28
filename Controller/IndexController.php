<?php
namespace ISPComplaintsCRM\Controller;

use ISPComplaintsCRM\Controller\ViewSetter;


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

            'text' => 'Index Page',
            

        ]);
    }
}