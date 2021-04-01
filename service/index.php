<?php
try {
    require_once __DIR__ . '/Routing.php';

    $files = scandir(__DIR__);
    foreach ($files as $file) {
        if (preg_match('/.*rest\\.php/', strtolower($file))) {
            include_once __DIR__ . '/' . $file;
        }
    }

    $routing = Routing::getCopy();
    $routing->trueCors('*', 'origin, content-type, accept, authorization');
    $routing = $routing->routingRequestProcessing();

    if(!$routing) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 400 Error');
        die();
    }
} catch (Exception $e) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Fatal error');
    header('Content-Type: application/json');
    die(json_encode(array('error' => $e -> getMessage())));
}
