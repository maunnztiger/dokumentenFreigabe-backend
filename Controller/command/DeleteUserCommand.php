<?php
namespace dokumentenFreigabe\Controller\command;

use dokumentenFreigabe\Controller\command\Command;
use dokumentenFreigabe\Library\NotFoundException;
use dokumentenFreigabe\Application;

class DeleteUserCommand extends Command
{
    public function execute(CommandContext $context): bool
    {
        $userName = $context->get('userName');
                
        if (!(Application::getModel('Admin'))->deleteUserData($userName)) {
            throw new NotFoundException("User could not be deleted");
            return false;
        }
        return true;
    }
}