<?php

namespace ISPComplaintsCRM\Library;

use ISPComplaintsCRM\Library\NotFoundException;

require 'NotFoundException.php';

class View
{

    protected $path, $controller, $action, $vars = [];

    public function __construct($path, $controllerName, $actionName)
    {
        $this->path = $path;
        $this->controller = $controllerName;
        $this->action = $actionName;
    }

    public function setVars(array $vars)
    {
        foreach ($vars as $key => $val) {
            $this->vars[$key] = $val;
        }
    }

    public function render()
    {
        $fileName = $this->path . DIRECTORY_SEPARATOR . $this->controller . DIRECTORY_SEPARATOR . $this->action . '.phtml';
        if (!file_exists($fileName)) {
            throw new NotFoundException();
        }
        foreach ($this->vars as $key => $val) {
           // für jedes $key wird ein value eingefügt
           // $this->vars['text'] kann dann im template ausgelesen werden
            $this->vars[$key] = $val;
        }
        include $fileName;
    }
}
