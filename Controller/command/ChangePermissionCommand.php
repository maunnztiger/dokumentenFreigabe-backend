<?php
namespace dokumentenFreigabe\Controller\command;

use dokumentenFreigabe\Controller\command\Command;
use dokumentenFreigabe\Library\NotFoundException;
use dokumentenFreigabe\Application;
use dokumentenFreigabe\Model\VideoNames;
use dokumentenFreigabe\Model\Videostream;


class ChangePermissionCommand extends Command
{
    private $userName = "";

    

    public function execute(CommandContext $context): bool
    {
        $object = Application::getModel('Admin');
        $this->userName = $context->get('userName');
        $object->changePermission($this->userName);
        
        if (!$object->changePermission($this->userName)) {
            throw new Exception("Permission could not be changed");
            return false;
        }

        $context->addParam('bool', true);
     
        return true;
    }
}