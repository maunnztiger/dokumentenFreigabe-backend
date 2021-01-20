<?php
namespace schoolyard\Controller\command;

use schoolyard\Controller\command\Command;
use schoolyard\Library\NotFoundException;
use schoolyard\Application;


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