<?php

/**
 * An example of a project-specific implementation.
 *
 *
 * @param string $class The fully-qualified class name.
 * @return void
 */
spl_autoload_register(function ($className) {

    //project specific namespace prefix
    $prefix = 'dokumentenFreigabe\\';
    $baseDir = __DIR__ . '/';
    $len = strlen($prefix);

    if (strncmp($prefix, $className, $len) !== 0) {
        return;
    }

    $relativeClass = substr($className, $len);
    $fileName = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';

    if (file_exists($fileName)) {
        require $fileName;
    }

});
