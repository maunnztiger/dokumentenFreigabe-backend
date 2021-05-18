<?php
namespace dokumentenFreigabe\Controller\command;

use dokumentenFreigabe\Controller\command\Command;
use dokumentenFreigabe\Library\NotFoundException;
use dokumentenFreigabe\Application;



class AddPDFNameToDatabaseCommand extends Command
{
  
    private $newPDFName = "";

    

    public function execute(CommandContext $context): bool
    {
        $object = Application::getModel('Admin');
       
        $this->newPDFName = $context->get('newPDFName');
      
        
        if (!$object->addPDFToDatabase($this->newPDFName)) {
            throw new NotFoundException("Entry could not be set to Database");
            return false;
        }

        $context->addParam('bool', true);
     
        return true;
    }
}