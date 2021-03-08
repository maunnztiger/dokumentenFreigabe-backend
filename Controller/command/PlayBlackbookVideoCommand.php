<?php
namespace dokumentenFreigabe\Controller\command;

use dokumentenFreigabe\Controller\command\Command;
use dokumentenFreigabe\Library\NotFoundException;
use dokumentenFreigabe\Application;
use dokumentenFreigabe\Model\VideoNames;
use dokumentenFreigabe\Model\Videostream;


class PlayBlackbookVideoCommand extends Command
{
    public function execute(CommandContext $context): bool
    {

        if (!isset($context)) {
            throw new CommandNotFoundException("Wrong Context given");
            return false;
        }


       
        $receiver = Application::getModel('BlackBookVideo');
        if ($receiver->displayVideo()) {
            
            return true;
        }

    }
}