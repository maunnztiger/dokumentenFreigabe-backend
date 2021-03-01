<?php

namespace dokumentenFreigabe\Model;

use dokumentenFreigabe\Library\NotFoundException;

class XMLData {

    private $path = "./data.xml";


    public function getXML(){
        return $xmlString = file_get_contents($this->path);
    
        
    }
}
 