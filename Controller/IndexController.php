<?php
namespace dokumentenFreigabe\Controller;

use dokumentenFreigabe\Application;
use ZipArchive;
use DOMDocument;

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

        if (isset($_POST['userName']) && isset($_POST['password']) &&
            $_SERVER['REQUEST_METHOD'] == 'POST') {

            $invoker = Application::getModel('Invoker');
            $context = $invoker->getContext();
            $context->addParam('action', 'login');
            $context->addParam('username', $_POST['userName']);
            $context->addParam('password', $_POST['password']);
            $invoker->process();
            $this->dataObj = Application::getModel('Registry');
            $this->session->setSessionName('dataObj', $this->dataObj);
            $this->dataObj->setParam('permission', $context->get('permission'));
            $this->dataObj->setParam('userName', $_POST['userName']);
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
            if(!empty($context->get('videoNames'))){
                echo json_encode(array(
                    "videoNames" => $context->get('videoNames'),
                    "videoDurationTime" => $context->get('videoDurationTime'),
                    "plays" => $context->get('plays'),
                ));
            } else {
                echo json_encode(array('data'=> 'no data loaded'));
            }
            
        }
           
    }
 
    public function uploadVideoAction(){
        if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES["filename"])){
           
            var_dump($_FILES["filename"]);
            $filename = $_FILES['filename']['name'];
            if(isset($filename) && !empty($filename)){
               
                $uploadDir = "C:\\xampp\\htdocs\\assets\\";
                $uploadfile = $uploadDir . basename($_FILES['filename']['name']);
                
                if (move_uploaded_file($_FILES['filename']['tmp_name'], $uploadfile) !== false) {
                    $newFilename = trim($filename, '.mp4');
                    $invoker = Application::getModel('Invoker');
                    $context = $invoker->getContext();
                    $context->addParam('action', 'addVideoNameToDatabase');
                    $context->addParam('newVideoName', $newFilename);
                    $invoker->process();
                    echo $context->get('bool');
                }  else {
                    echo "Cannot create file (" . basename($uploadfile) . ")";
                }

               
            } else{
                echo 'please choose a file';
            }
        } else {
            echo 'not set';
        }
        
    }

    public function playVideoAction(){
            
        $this->dataObj = $this->session->getSessionName('dataObj');
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $this->dataObj->setParam('videoName', $_POST['name']);
        }      
                
        if($_SERVER['REQUEST_METHOD'] === 'GET' && $this->dataObj->get('permission') == 'Admin' ){
            
            
            $invoker = Application::getModel('Invoker');
            $context = $invoker->getContext();
            $context->addParam('action', 'playVideo');
            $context->addParam('videoName', $this->dataObj->get('videoName'));
            $invoker->process();
        } 
        
        if($_SERVER['REQUEST_METHOD'] === 'GET'  && $this->dataObj->get('permission') == 'Employee'){
            $invoker = Application::getModel('Invoker');
            $context = $invoker->getContext();
            $context->addParam('action', 'getVideoPermissions');
            $context->addParam('videoName', $this->dataObj->get('videoName'));
            $context->addParam('userName', $this->dataObj->get('userName'));
            $invoker->process();
            
            $permissions = $context->get('permissions');
            if(!is_null($permissions)){
                $invoker = Application::getModel('Invoker');
                $context = $invoker->getContext();
                $context->addParam('action', 'playVideo');
                $context->addParam('videoName', $this->dataObj->get('videoName'));
                $invoker->process();
            } else {
               echo 'Keine Berechtigung!';
            }
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'GET'  && $this->dataObj->get('permission') == 'Customer'){
            $invoker = Application::getModel('Invoker');
            $context = $invoker->getContext();
            $context->addParam('action', 'getVideoPermissions');
            $context->addParam('videoName', $this->dataObj->get('videoName'));
            $context->addParam('userName', $this->dataObj->get('userName'));
            $invoker->process();
                
            $permissions = $context->get('permissions');
            if(!is_null($permissions)){
                $invoker = Application::getModel('Invoker');
                $context = $invoker->getContext();
                $context->addParam('action', 'playVideo');
                $context->addParam('videoName', $this->dataObj->get('videoName'));
                $invoker->process();
            } else {
                echo 'Keine Berechtigung!';
            }    
            
        }       
                  
       
    }

    public function getPdfBinaryAction(){
       
        $this->dataObj = $this->session->getSessionName('dataObj');
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $pdfName = trim($_POST['fileName'], '.pdf');
            $this->dataObj->setParam('pdfName', $pdfName);
            $this->dataObj->setParam('file', "C:\\xampp\\htdocs\\PDF_Files\\".$_POST['fileName']);
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'GET' && $this->dataObj->get('permission') == 'Admin'){
           
            $invoker = Application::getModel('Invoker');
            $context = $invoker->getContext();
            $context->addParam('action', 'getPDFBinary');
            $invoker->process();


        }  

        if($_SERVER['REQUEST_METHOD'] === 'GET' && $this->dataObj->get('permission') == 'Employee'){
            $invoker = Application::getModel('Invoker');
            $context = $invoker->getContext();
            $context->addParam('action', 'getPDFPermissions');
            $context->addParam('pdfName', $this->dataObj->get('pdfName'));
            $context->addParam('userName', $this->dataObj->get('userName'));
            $invoker->process();
            
            $permissions = $context->get('permissions');
            if(!is_null($permissions)){
                $invoker = Application::getModel('Invoker');
                $context = $invoker->getContext();
                $context->addParam('action', 'getPDFBinary');
                $invoker->process();

            } else {
               echo 'Keine Berechtigung!';
            }
        }

        if($_SERVER['REQUEST_METHOD'] === 'GET' && $this->dataObj->get('permission') == 'Customer'){
            $invoker = Application::getModel('Invoker');
            $context = $invoker->getContext();
            $context->addParam('action', 'getPDFPermissions');
            $context->addParam('pdfName', $this->dataObj->get('pdfName'));
            $context->addParam('userName', $this->dataObj->get('userName'));
            $invoker->process();
            
            $permissions = $context->get('permissions');
            if(!is_null($permissions)){
                $invoker = Application::getModel('Invoker');
                $context = $invoker->getContext();
                $context->addParam('action', 'getPDFBinary');
                $invoker->process();

            } else {
               echo 'Keine Berechtigung!';
            }
        }  
    }

    public function uploadFileAction(){
        $this->dataObj = $this->session->getSessionName('dataObj');
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $filename = $_FILES['filename']['name'];
            if(isset($filename) && !empty($filename)){
               
                $uploadDir = "C:\\xampp\\htdocs\\PDF_Files\\";
                $uploadfile = $uploadDir . basename($_FILES['filename']['name']);
                
                if (move_uploaded_file($_FILES['filename']['tmp_name'], $uploadfile) !== false) {
                    $newFilename = trim($filename, '.pdf');
                    $invoker = Application::getModel('Invoker');
                    $context = $invoker->getContext();
                    $context->addParam('action', 'addPDFNameToDatabase');
                    $context->addParam('newPDFName', $newFilename);
                    $invoker->process();
                    $context = $invoker->getContext();
                    $context->addParam('action', 'setPDFPermission');
                    $context->addParam('userName', $this->dataObj->get('userName'));
                    $context->addParam('pdfName', $newFilename);
                    $invoker->process();
                    echo $context->get('bool');
                }  else {
                    echo "Cannot create file (" . basename($uploadfile) . ")";
                }

               
            } else{
                echo 'please choose a file';
            }
        } else {
            echo 'not set';
        }
    }

    public function uploadDocumentAction(){
        $this->dataObj = $this->session->getSessionName('dataObj');
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $filename = $_FILES['filename']['name'];
            if(isset($filename) && !empty($filename)){
               
                $uploadDir = "C:\\xampp\\htdocs\\Docx_Files\\";
                $uploadfile = $uploadDir . basename($_FILES['filename']['name']);
                $newFilename = trim($filename, '.docx');
                if (move_uploaded_file($_FILES['filename']['tmp_name'], $uploadfile) !== false) {
                    $invoker = Application::getModel('Invoker');
                    $context = $invoker->getContext();
                    $context->addParam('action', 'addDocumentNameToDatabase');
                    $context->addParam('documentName', $newFilename);
                    $invoker->process();
                    $context = $invoker->getContext();
                    $context->addParam('action', 'setDocxPermission');
                    $context->addParam('userName', $this->dataObj->get('userName'));
                    $context->addParam('docxName', $newFilename);
                    $invoker->process();
                    echo $context->get('bool');
                }  else {
                    echo "Cannot create file (" . basename($uploadfile) . ")";
                }

               
            } else{
                echo 'please choose a file';
            }
        } else {
            echo 'not set';
        }
    }

    public function getWordDocumentsListAction(){
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $invoker = Application::getModel('Invoker');
            $context = $invoker->getContext();
            $context->addParam('action', 'getWordDocumentsList');
            $invoker->process();
            $fileNames = $context->get('fileNames');
            echo json_encode($fileNames);
        }
    }

    public function getDocxBinaryAction(){
        $this->dataObj = $this->session->getSessionName('dataObj');
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $this->dataObj->setParam('fileName', $_POST['fileName']);
            $this->dataObj->setParam('file', "C:\\xampp\\htdocs\\Docx_Files\\".$_POST['fileName'].'.docx');
           
        }


        if($_SERVER['REQUEST_METHOD'] === 'GET' && $this->dataObj->get('permission') == 'Admin'){
          
        } 

        if($_SERVER['REQUEST_METHOD'] === 'GET' && $this->dataObj->get('permission') == 'Employee'){
            
            $invoker = Application::getModel('Invoker');
            $context = $invoker->getContext();
            $context->addParam('action', 'getDocxPermissions');
            $context->addParam('docxName',$this->dataObj->get('fileName'));
            $context->addParam('userName', $this->dataObj->get('userName'));
            $invoker->process();
            
            $permissions = $context->get('permissions');
            if(!is_null($permissions)){
                $filePath = $this->dataObj->get('file');
              if (file_exists($this->dataObj->get('file'))) {
                  $zip = new ZipArchive;
                  $dataFile = 'word/document.xml';
                  // Open received archive file
                  if (true === $zip->open($filePath)) {
                      // If done, search for the data file in the archive
                      if (($index = $zip->locateName($dataFile)) !== false) {
                          // If found, read it to the string
                          $data = $zip->getFromIndex($index);
                          // Close archive file
                          $zip->close();
                          // Load XML from a string
                          // Skip errors and warnings
                          $xml = DOMDocument::loadXML($data, LIBXML_HTML_NOIMPLIED  | LIBXML_HTML_NODEFDTD);
                          // Return data without XML formatting tags
              
                          $xmldata = $xml->saveXML();
                         
                          $contents = strip_tags($xmldata, '<w:p><w:u><w:i><w:b>');
                          $contents = preg_replace("/(<(\/?)w:(.)[^>]*>)\1*/", "<$2$3>", $contents);
  
                          $dom = new DOMDocument('1.0', 'utf-8');
                          @$dom->loadHTML('<?xml encoding="utf-8" ?>' .$contents, LIBXML_HTML_NOIMPLIED  | LIBXML_HTML_NODEFDTD);
                          $contents = $dom->saveHTML();
  
                          $contents = preg_replace('~<([ibu])>(?=(?:\s*<[ibu]>\s*)*?<\1>)|</([ibu])>(?=(?:\s*</[ibu]>\s*)*?</?\2>)|<p></p>~s', "", $contents);
  
                          echo json_encode($contents);
                        
                      }
                   }
                  // In case of failure return empty string
                  echo json_encode("");
                  
              } else {
                  echo json_encode('file not found');
              }
            } else {
               echo 'Keine Berechtigung!';
            }
        } 




    }

}
