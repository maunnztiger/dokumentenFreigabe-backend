<?php
namespace dokumentenFreigabe\Controller\command;

use dokumentenFreigabe\Controller\command\Command;
use dokumentenFreigabe\Library\NotFoundException;
use dokumentenFreigabe\Application;



class AddVideoNameToDatabaseCommand extends Command
{
  
    private $newVideoName = "";

    

    public function execute(CommandContext $context): bool
    {
        $object = Application::getModel('Admin');
       
        $this->newVideoName = $context->get('newVideoName');
      
        
        if (!$object->addVideoNameToDatabase($this->newVideoName)) {
            throw new NotFoundException("Entry could not be set to Database");
            return false;
        }

        $context->addParam('bool', true);
     
        return true;
    }
}