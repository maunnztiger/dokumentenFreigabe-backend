<?php
namespace dokumentenFreigabe\Controller;

use dokumentenFreigabe\Application;

class AdminController
{

    private $session;
    private $dataObj;

    public function __construct()
    {
        $this->session = Application::getModel('Session');
        $this->dataObj = $this->session->getSessionName('dataObj');
    }

    public function adminViewAction()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            echo json_encode(['Menu: Right click here']);
        }
    }

    public function listUsersAction()
    {

        if ($this->dataObj->get('permission') == 'Admin' &&
            $_SERVER['REQUEST_METHOD'] == 'GET') {
            $invoker = Application::getModel('Invoker');
            $context = $invoker->getContext();
            $context->addParam('action', 'getUserList');
            $invoker->process();
            echo json_encode($context->get('userList'));
        }

    }

    public function getUserAction()
    {
        if (isset($_POST['name']) && $this->dataObj->get('permission') === 'Admin') {

          $this->dataObj->setParam('user', (Application::getModel('Admin'))->getUser($_POST['name']));
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            header('HTTP/1.0 200 OK');
            echo json_encode(array($this->dataObj->get('user')));
        }

    }

    public function updateUserAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT' && $this->dataObj->get('permission') == 'Admin') {

            parse_str(file_get_contents('php://input'), $_PUT);
            $invoker = Application::getModel('Invoker');
            $context = $invoker->getContext();
            $context->addParam('action', 'updateUser');
            $context->addParam('group', $_PUT['groupName']);
            $context->addParam('user-id', $_PUT['user-id']);
            $context->addParam('department', $_PUT['department']);
            $invoker->process();
            header('HTTP/1.0 204 OK');
            $this->dataObj->setParam('updatedUser', $context->get('updatedUser'));
        }
      
        echo json_encode($this->dataObj->get('updatedUser'));

    }

    public function deleteUserAction()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && $this->dataObj->get('permission') == 'Admin') {

            $invoker = Application::getModel('Invoker');
            $context = $invoker->getContext();
            $context->addParam('action', 'deleteUser');
            $context->addParam('userName', $_GET['name']);
            $invoker->process();
            header('HTTP/1.0 204 OK');
        }

    }

    public function getXMLBinaryAction(){
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            
            $file = './data.xml';

            if (file_exists($file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($file).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                readfile($file);
                exit;
            } else {
                echo json_encode('file not found');
            }
           
        }
    }

    public function getPdfBinaryAction(){
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            
            $file = "C:\\xampp\\htdocs\\PDF_Files\\IHK_Zeugnis.pdf";

            if (file_exists($file)) {
                header('Content-Type: application/pdf');
                header('Content-Length: ' . filesize($file));
                readfile($file);
                exit;
            } else {
                echo json_encode('file not found');
            }
           
        }
    }

}
