<?php
namespace schoolyard\Controller\command;

use schoolyard\Controller\command\Command;
use schoolyard\Library\NotFoundException;
use schoolyard\Model\Auth;

class LoginCommand extends Command
{
    public function execute(CommandContext $context): bool
    {
        
        $user = $context->get('username');
        $pass = $context->get('password');
        $manager = new Auth($user, $pass);
        $manager->login();
        $permission = $manager->getUserGroup($pass);
        
        if (is_null($permission)) {
            throw new NotFoundException("Licence not found");
            return false;
        }

        $context->addparam('permission', $permission->groupname);
        return true;
    }
}