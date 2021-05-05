<?php
define('DEFAULT_CONTROLLER', 'topic');
define('DEFAULT_ACTION', 'index');

/**
 * main router for every request
 * crate a copy from controller
 * GET and POST request
 */
function run() {
    try {
        if(!filter_input(INPUT_GET, 'controller')) {
            $_GET['controller'] = DEFAULT_CONTROLLER;
        }
        if (!filter_input(INPUT_GET, 'action')) {
            $_GET['action'] = DEFAULT_ACTION;
        }

        $controllerGet = filter_input(INPUT_GET, 'controller', FILTER_SANITIZE_SPECIAL_CHARS);
        $controller = loadController($controllerGet);
        $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS);
        $controller->$action();
    } catch (Exception $e) {
        die('Fatal Error!' . $e->getMessage());
    }
}

/**
 * load controller file, and create a controller copy
 * @param $controllerName controller name in the url
 * @param object The controller copy
 */
function loadController($controllerName) {
    if($controllerName === null) {
        header('LOCATION: index.php?controller=topic&action=index');
    }
    $controllerClassName = getControllerClassName($controllerName);
    require_once __DIR__ . '/controller/' . $controllerClassName . '.php';
    return new $controllerClassName();
}

/**
 * get class name from url
 * @param string $controllerName
 * @return string Name of controller
 */
function getControllerClassName($controllerName) {
    return ucfirst($controllerName) . 'Controller';
}

run();
