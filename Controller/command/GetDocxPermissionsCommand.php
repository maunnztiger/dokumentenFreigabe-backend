<?php
namespace dokumentenFreigabe\Controller\command;

use dokumentenFreigabe\Controller\command\Command;
use dokumentenFreigabe\Library\NotFoundException;
use dokumentenFreigabe\Application;


class GetDocxPermissionsCommand extends Command
{
    public function execute(CommandContext $context): bool
    {
        $docxName = $context->get('docxName');
        $user = $context->get('userName');
        $permissions = (Application::getModel('Admin'))->getDocxPermissions($user, $docxName);
       
        $context->addParam('permissions', $permissions);
        return true;
    }
}