<?php
namespace ISPComplaintsCRM\Controller\command;

use ISPComplaintsCRM\Library\CommandNotFoundException;

require_once 'autoload.php';

class CommandFactory
{

    private static $dir = 'command';

    public static function getCommand(string $action = 'Default'): Command
    {
        if (preg_match('/\W/', $action)) {
            throw new Exception("illegal character found");
        }

        $class = __NAMESPACE__ . DIRECTORY_SEPARATOR . UCFirst(strtolower($action)) . "Command";

        if (!class_exists($class)) {
            throw new CommandNotFoundException("no $class class located");
        }

        $cmd = new $class;

        return $cmd;
    }
}