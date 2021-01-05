<?php
namespace ISPComplaintsCRM\Controller\command;

use ISPComplaintsCRM\Controller\command\Command;
use ISPComplaintsCRM\Library\NotFoundException;
use ISPComplaintsCRM\Model\Auth;

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