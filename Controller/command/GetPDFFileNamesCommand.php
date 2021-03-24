<?php
namespace dokumentenFreigabe\Controller\command;

use dokumentenFreigabe\Controller\command\Command;
use dokumentenFreigabe\Library\NotFoundException;
use dokumentenFreigabe\Application;
use dokumentenFreigabe\Model\VideoNames;
use dokumentenFreigabe\Model\Videostream;


class GetPDFFileNamesCommand extends Command
{

    private $basePath = "C:\\xampp\\htdocs\\PDF_Files";


    public function execute(CommandContext $context): bool
    {

        if (!isset($context)) {
            throw new CommandNotFoundException("Wrong Context given");
            return false;
        }
        
        $receiver = Application::getModel('FileParams');
        $fileNames = $receiver->getFileNames($this->basePath);
        if (!is_null($fileNames)){
            $context->addParam('fileNames', $fileNames);
            return true;
        }

    }
}