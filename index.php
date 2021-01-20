<?php
require './autoload.php';

//get the requested URL

$url = (isset($_GET['_url']) ? $_GET['_url'] : '');
$urlparts = explode('/', $url);

//build the controller class

$controllerName = (isset($urlparts[0]) && $urlparts[0] ? $urlparts[0] : 'index');
$controllerClassName = '\\schoolyard\\Controller\\' . ucfirst($controllerName) . 'Controller';

//build the action method

$actionName = (isset($urlparts[1]) && $urlparts[1] ? $urlparts[1] : 'index');
$actionMethodName = $actionName . 'Action';

try {
    if (!class_exists($controllerClassName)) {

        throw new \schoolyard\Library\NotFoundException();

    }

    $controller = new $controllerClassName();

    if (!$controller instanceof \schoolyard\Controller\ViewSetter || !method_exists($controller, $actionMethodName)) {
        throw new \schoolyard\Library\NotFoundException();
    }

    $view = new \schoolyard\Library\View(__DIR__ . DIRECTORY_SEPARATOR . 'views', $controllerName, $actionName);
    $controller->setView($view);
    $controller->$actionMethodName();
    $view->render();

} catch (\schoolyard\Library\NotFoundException $e) {
    http_response_code(404);
    echo 'Page not found: ' . $controllerClassName . '::' . $actionMethodName;
} catch (\Exception $e) {
    http_response_code(500);
    echo 'Exception: ' . $e->getMessage() . ' ' . $e->getTraceAsString();
}
