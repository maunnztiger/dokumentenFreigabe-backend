<?php
namespace dokumentenFreigabe\Controller\command;

use dokumentenFreigabe\Controller\command\Command;
use dokumentenFreigabe\Library\NotFoundException;
use dokumentenFreigabe\Application;



class SetPDFPermissionCommand extends Command
{
    private $userName = "";
    private $pdfName = "";

    

    public function execute(CommandContext $context): bool
    {
        $object = Application::getModel('Admin');
        $this->userName = $context->get('userName');
        $this->pdfName = $context->get('pdfName');
      
        
        if (!$object->setPDFPermission($this->userName,$this->pdfName)) {
            throw new NotFoundException("Permission could not be changed");
            return false;
        }

        $context->addParam('bool', true);
     
        return true;
    }
}