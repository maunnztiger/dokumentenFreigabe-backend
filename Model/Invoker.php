<?php

namespace schoolyard\Model;

use schoolyard\Controller\command\CommandContext;
use schoolyard\Controller\command\CommandFactory;

class Invoker extends \Exception
{

    private $context;

    public function __construct()
    {
        $this->context = new CommandContext();
    }

    public function getContext(): CommandContext
    {
        return $this->context;
    }

    public function process()
    {
        $action = $this->context->get('action');
        $action = (is_null($action)) ? "default" : $action;
        $cmd = CommandFactory::getCommand($action);

        if (!$cmd->execute($this->context)) {
            throw new \Exception("Command cannot been processed");
            return false;
        }

    }

}
