<?php
namespace dokumentenFreigabe\Controller\command;

use dokumentenFreigabe\Controller\command\Command;
use dokumentenFreigabe\Library\NotFoundException;
use dokumentenFreigabe\Application;


class GetVideoPermissionsCommand extends Command
{
    public function execute(CommandContext $context): bool
    {
        $video = $context->get('videoName');
        $user = $context->get('userName');
        $permissions = (Application::getModel('Admin'))->getVideoPermissions($user, $video);
        
        if (is_null($permissions)) {
            $context->addParam('permissions', $permissions);
            return true;
        }
      
        $context->addParam('permissions', $permissions);
        return true;
    }
}