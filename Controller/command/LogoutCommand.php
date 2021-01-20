<?php
namespace schoolyard\Controller\command;

use schoolyard\Controller\command\Command;
use schoolyard\Library\NotFoundException;
use schoolyard\Model\Auth;

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