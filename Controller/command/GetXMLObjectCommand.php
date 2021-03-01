<?php
namespace dokumentenFreigabe\Controller\command;

use dokumentenFreigabe\Controller\command\Command;
use dokumentenFreigabe\Library\NotFoundException;
use dokumentenFreigabe\Application;


class GetXMLObjectCommand extends Command
{
    public function execute(CommandContext $context): bool
    {
        $xmlObject = (Application::getModel('XMLData'))->getXML();
        if (is_null(  $xmlObject )) {
            throw new NotFoundException("Users not found");
            return false;
        }
      
        $context->addParam('xmlObject', $xmlObject);
        return true;
    }
}