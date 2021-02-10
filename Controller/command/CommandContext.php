<?php
namespace dokumentenFreigabe\Controller\command;

class CommandContext
{
    private $params = array();
    private $error = "";

    public function __construct()
    {
        $this->params = $_REQUEST;
    }

    public function addParam(string $key, $val)
    {
        $this->params[$key] = $val;
    }

    public function get(string $key)
    {
        if (isset($this->params[$key])) {
            return $this->params[$key];
        }
        return null;
    }
}
