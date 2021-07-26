<?php
namespace dokumentenFreigabe\Controller\command;

use dokumentenFreigabe\Controller\command\Command;
use dokumentenFreigabe\Library\NotFoundException;
use dokumentenFreigabe\Application;



class GetPDFBinaryCommand extends Command
{
    public function execute(CommandContext $context): bool
    {

        if (!isset($context)) {
            throw new CommandNotFoundException("Wrong Context given");
            return false;
        }

       
       
        $receiver = Application::getModel('PDFDisplay');
        if ($receiver->displayPDF()) {
            
            return true;
        }

    }
}