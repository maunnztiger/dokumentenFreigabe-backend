<?php
namespace dokumentenFreigabe\Model;
use dokumentenFreigabe\Application;
class PDFDisplay {

    public function __construct(){  
        $this->session = Application::getModel('Session');
        $this->dataObj = $this->session->getSessionName('dataObj');
    }


    public function displayDocx(){

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
    }
}