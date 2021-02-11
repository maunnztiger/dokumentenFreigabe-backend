<?php
namespace dokumentenFreigabe\Controller\command;

use dokumentenFreigabe\Controller\command\Command;
use dokumentenFreigabe\Library\NotFoundException;
use dokumentenFreigabe\Application;

class UpdateUserCommand extends Command
{
    public function execute(CommandContext $context): bool
    {
        $id= $context->get('user-id');
        $group = $context->get('group');
        $department = $context->get('department');
        $updatedUser = (Application::getModel('Admin'))->updateUser($group, $department, $id);
        if (is_null($updatedUser)) {
            throw new NotFoundException("Licence not found");
            return false;
        }

        $context->addparam('updatedUser', $updatedUser);
        return true;
    }
}