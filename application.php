<?php

namespace schoolyard;

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
            throw new CommandNotFoundException("no $class class located");
        }

        $cmd = new $class;

        return $cmd;

    }
}
