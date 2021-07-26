<?php
namespace dokumentenFreigabe\Model;
use dokumentenFreigabe\Application;
class PDFDisplay {

    public function __construct(){  
        $this->session = Application::getModel('Session');
        $this->dataObj = $this->session->getSessionName('dataObj');
    }


    public function displayPDF(){

        if (file_exists($this->dataObj->get('file'))) {
            header('Content-Type: application/pdf');
            header('Content-Length: ' . filesize($this->dataObj->get('file')));
            readfile($this->dataObj->get('file'));
            exit;
        } else {
            echo json_encode('file not found');
        }
    }
}