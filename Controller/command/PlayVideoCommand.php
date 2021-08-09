<?php
namespace dokumentenFreigabe\Controller\command;

use dokumentenFreigabe\Controller\command\Command;
use dokumentenFreigabe\Library\NotFoundException;
use dokumentenFreigabe\Application;
use dokumentenFreigabe\Model\VideoNames;
use dokumentenFreigabe\Model\Videostream;


class PlayVideoCommand extends Command
{
    public function execute(CommandContext $context): bool
    {

        if (!isset($context)) {
            throw new CommandNotFoundException("Wrong Context given");
            return false;
        }


        $videoName = $context->get("videoName");
      
        $receiver = Application::getModel('Video');
       
        if ($receiver->displayVideo($videoName)) {
         
            return true;
        }

    }
}