<?php
require './autoload.php';

//get the requested URL

$url = (isset($_GET['_url']) ? $_GET['_url'] : '');
$urlparts = explode('/', $url);

//build the controller class

$controllerName = (isset($urlparts[0]) && $urlparts[0] ? $urlparts[0] : 'index');
$controllerClassName = '\\ISPComplaintsCRM\\Controller\\' . ucfirst($controllerName) . 'Controller';

//build the action method

$actionName = (isset($urlparts[1]) && $urlparts[1] ? $urlparts[1] : 'index');
$actionMethodName = $actionName . 'Action';

try {
    if (!class_exists($controllerClassName)) {

        throw new \ISPComplaintsCRM\Library\NotFoundException();

    }

    $controller = new $controllerClassName();

    if (!$controller instanceof \ISPComplaintsCRM\Controller\ViewSetter || !method_exists($controller, $actionMethodName)) {
        throw new \ISPComplaintsCRM\Library\NotFoundException();
    }

    $view = new \ISPComplaintsCRM\Library\View(__DIR__ . DIRECTORY_SEPARATOR . 'views', $controllerName, $actionName);
    $controller->setView($view);
    $controller->$actionMethodName();
    $view->render();

} catch (\ISPComplaintsCRM\Library\NotFoundException $e) {
    http_response_code(404);
    echo 'Page not found: ' . $controllerClassName . '::' . $actionMethodName;
} catch (\Exception $e) {
    http_response_code(500);
    echo 'Exception: ' . $e->getMessage() . ' ' . $e->getTraceAsString();
}
