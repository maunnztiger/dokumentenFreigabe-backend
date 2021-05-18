<?php
namespace dokumentenFreigabe\Controller\command;

use dokumentenFreigabe\Controller\command\Command;
use dokumentenFreigabe\Library\NotFoundException;
use dokumentenFreigabe\Application;



class RemoveFileNameFromDBCommand extends Command
{
  
    private $docxName = "";

    

    public function execute(CommandContext $context): bool
    {
        $object = Application::getModel('Admin');
       
        $this->docxName = $context->get('docxName');
      
        
        if (!$object->removeFileNameFromDatabase($this->docxName)) {
            throw new NotFoundException("Entry could not be set to Database");
            return false;
        }

        $context->addParam('bool', true);
     
        return true;
    }
}