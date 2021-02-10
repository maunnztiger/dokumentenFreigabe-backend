<?php
namespace dokumentenFreigabe\Controller\command;

use dokumentenFreigabe\Controller\command\Command;
use dokumentenFreigabe\Library\NotFoundException;
use dokumentenFreigabe\Application;


class GetUserListCommand extends Command
{
    public function execute(CommandContext $context): bool
    {
        $userList = (Application::getModel('Admin'))->getUserParams();
        if (is_null($userList)) {
            throw new NotFoundException("Users not found");
            return false;
        }

        $context->addParam('userList', $userList);
        return true;
    }
}