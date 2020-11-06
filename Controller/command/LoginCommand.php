<?php
namespace ISPComplaintsCRM\Controller\command;

use ISPComplaintsCRM\Controller\command\Command;
use ISPComplaintsCRM\Library\NotFoundException;
use ISPComplaintsCRM\Model\Auth;

class LoginCommand extends Command
{
    public function execute(CommandContext $context): bool
    {
        
        $user = $context->get('username');
        $pass = $context->get('password');
        $manager = new Auth($user, $pass);
        $permission = $manager->getUserGroup($pass);
        
        if (is_null($permission)) {
            throw new NotFoundException("Licence not found");
            return false;
        }

        $context->addparam('permission', $permission->groupname);
        return true;
    }
}