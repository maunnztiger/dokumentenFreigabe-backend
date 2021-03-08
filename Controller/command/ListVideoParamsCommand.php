<?php
namespace dokumentenFreigabe\Controller\command;

use dokumentenFreigabe\Controller\command\Command;
use dokumentenFreigabe\Library\NotFoundException;
use dokumentenFreigabe\Application;
use dokumentenFreigabe\Model\VideoNames;
use dokumentenFreigabe\Model\Videostream;


class ListVideoParamsCommand extends Command
{
    private $videoDurationTime = array();
    private $basePath = "C:/xampp/htdocs/assets/";
    private $plays = array();
    

    public function execute(CommandContext $context): bool
    {
        $object = Application::getModel('VideoParams');
        $videoNames = $object->getVideoNames();
        
        if (is_null($videoNames)) {
            throw new NotFoundException("Video-Names not found");
            return false;
        }

        $context->addParam('videoNames', $videoNames);

        foreach ($videoNames as $value) {
            $filepath = $this->basePath.$value;
            $this->videoDurationTime[] = $object->getVideoDurationTime($filepath);
            $this->plays[] = $object->getPlays($value);
           
            $context->addParam('videoDurationTime', $this->videoDurationTime);
            $context->addParam('plays', $this->plays);
        }

       
        return true;
    }
}