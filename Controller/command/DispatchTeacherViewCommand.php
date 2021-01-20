<?php

namespace schoolyard\Controller\command;

use schoolyard\Controller\command\Command;
use schoolyard\Library\CommandNotFoundException;
use schoolyard\Application;

class DispatchTeacherViewCommand extends Command {

    public function execute(CommandContext $context): bool
    {

        if (!isset($context)) {
            throw new CommandNotFoundException("Wrong Context given");
            return false;
        }
        
        $receiver = Application::getModel('ViewDispatcher');
        if ($receiver->dispatchView('Teacher')) {
            return true;
        }

    }
}