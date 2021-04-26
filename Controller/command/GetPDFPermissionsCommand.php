<?php
namespace dokumentenFreigabe\Controller\command;

use dokumentenFreigabe\Controller\command\Command;
use dokumentenFreigabe\Library\NotFoundException;
use dokumentenFreigabe\Application;


class GetPDFPermissionsCommand extends Command
{
    public function execute(CommandContext $context): bool
    {
        $pdfName = $context->get('pdfName');
        $user = $context->get('userName');
        $permissions = (Application::getModel('Admin'))->getPDFPermissions($user, $pdfName);
       
        $context->addParam('permissions', $permissions);
        return true;
    }
}