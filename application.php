<?php

namespace dokumentenFreigabe;
use dokumentenFreigabe\Library\NotFoundException;

class Application
{

    public static function getModel(string $className)
    {

        if (preg_match('/\W/', $className)) {
            throw new Exception("illegal character found");
        }

        $baseDir = __NAMESPACE__ . '\\Model\\';
        $class = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, UCFirst(strtolower($className)));

        if (!class_exists($class)) {
            throw new NotFoundException("no $class class located");
        }

        $cmd = new $class;

        return $cmd;

    }

    public static function getController(string $className){

        if (preg_match('/\W/', $className)) {
            throw new Exception("illegal character found");
        }

        $baseDir = __NAMESPACE__ . '\\Controller\\';
        $class = $baseDir. str_replace('\\', DIRECTORY_SEPARATOR, ucfirst($className));
        $cmd = new $class;

        return $cmd;
    }
}
