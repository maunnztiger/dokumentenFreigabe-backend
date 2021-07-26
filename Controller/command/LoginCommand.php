<?php
namespace dokumentenFreigabe\Controller\command;

use dokumentenFreigabe\Controller\command\Command;
use dokumentenFreigabe\Library\NotFoundException;
use dokumentenFreigabe\Model\Auth;

class LoginCommand extends Command
{
    public function execute(CommandContext $context): bool
    {
        
        $user = $context->get('username');
        $pass = $context->get('password');
        $manager = new Auth($user, $pass);
        $manager->login();
        $permission = $manager->getUserGroup($pass)->groupname;
        
        if (is_null($permission)) {
            throw new NotFoundException("Permission-Group could not be found");
            return false;
        }

        $context->addparam('permission', $permission);
        return true;
    }
}