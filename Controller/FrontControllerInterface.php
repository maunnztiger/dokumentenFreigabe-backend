<?php

namespace dokumentenFreigabe\Controller;

interface FrontControllerInterface {
    public function setController($controller);
    public function setAction($action);

    public function run();
}