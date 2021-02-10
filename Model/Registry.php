<?php

namespace dokumentenFreigabe\Model;

class Registry {
    private $param = array();

    public function setParam($key, $value){
        $this->param[$key] = $value;
    }

    public function get($key){
        return $this->param[$key];
    }
}