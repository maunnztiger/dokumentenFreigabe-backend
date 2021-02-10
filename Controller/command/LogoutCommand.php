<?php
namespace dokumentenFreigabe\Controller\command;

use dokumentenFreigabe\Controller\command\Command;
use dokumentenFreigabe\Library\NotFoundException;
use dokumentenFreigabe\Model\Auth;

class LogoutCommand extends Command
{
    public function execute(CommandContext $context): bool
    {
     
        
        $manager = new Auth('', '');
        if($manager->logout($from  = false)){
            return true;
        }
       
    
    }
}