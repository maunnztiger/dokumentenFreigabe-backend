<?php
namespace dokumentenFreigabe\Controller;

use dokumentenFreigabe\Application;

class IndexController
{

    private $session;
    private $dataObj;
    private $token = "3c28d89b80f70302b04fce2a1451f6ea";

    public function __construct()
    {
        $this->session = Application::getModel('Session');

    }

    public function indexAction()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $headers = getallheaders();

            if (isset($headers["Authorization"]) && $headers["Authorization"] !== '') {
               
                if (strcmp($this->token, $headers["Authorization"]) !== 0) {
                    http_response_code(403);
                    echo 'No Permission to access this server!';
                } else {
                    header('HTTP/1.0 200 OK');
                    echo json_encode(['Login']);

                }
            } else {
                var_dump($headers = getallheaders());
                http_response_code(403);
                echo 'No Permission to access this server!';
            }

        }

    }

    public function formularAction()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
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
            $this->dataObj->setParam('userName', $_POST['user']);
            echo json_encode(strtolower($context->get('permission')));
        }
    }

    public function logoutAction()
    {
        $invoker = Application::getModel('Invoker');
        $context = $invoker->getContext();
        $context->addParam('action', 'logout');
        $invoker->process();
        header('HTTP/1.0 200 OK');
        echo json_encode(true);

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

    public function listVideoParamsAction(){

        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $invoker = Application::getModel('Invoker');
            $context = $invoker->getContext();
            $context->addParam('action', 'listVideoParams');
            $invoker->process();
    
            echo json_encode(array(
                'videos' => $context->get('videoNames'),
                'videoDurationTime' => $context->get('videoDurationTime'),
                'plays' => $context->get('plays'),
            ));
        }
           
    }

    public function playVideoAction(){
            
        $this->dataObj = $this->session->getSessionName('dataObj');
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $this->dataObj->setParam('videoName', $_POST['name']);
        }      
                
        if($_SERVER['REQUEST_METHOD'] === 'GET' && $this->dataObj->get('videoName') === "BlackbookSessions" && $this->dataObj->get('permission') == 'Admin' ){
            $invoker = Application::getModel('Invoker');
            $context = $invoker->getContext();
            $context->addParam('action', 'playBlackbookVideo');
            $invoker->process();
        } 
        
        if($_SERVER['REQUEST_METHOD'] === 'GET' && $this->dataObj->get('videoName') === "BlackbookSessions" && $this->dataObj->get('permission') == 'Employee'){
            $invoker = Application::getModel('Invoker');
            $context = $invoker->getContext();
            $context->addParam('action', 'getVideoPermissions');
            $context->addParam('videoName', $this->dataObj->get('videoName'));
            $context->addParam('userName', $this->dataObj->get('userName'));
            $invoker->process();
            
            $permissions = $context->get('permissions');
            if(!is_null($permissions)){
                $context->addParam('action', 'playBlackbookVideo');
                $invoker->process();
            } else {
               echo 'Keine Berechtigung!';
            }
                
            
        }        
                  
        if($_SERVER['REQUEST_METHOD'] === 'GET' && $this->dataObj->get('videoName') === "Detroit"){
            $invoker = Application::getModel('Invoker');
            $context = $invoker->getContext();
            $context->addParam('action', 'playDetroitVideo');
            $invoker->process();
        }   
    }

}
