<?php
class Routing {
    private static $routing_singleton = null;

    public static function getCopy() {
        if(self::$routing_singleton == null) {
            self::$routing_singleton = new Routing();
        }
        return self::$routing_singleton;
    }

    private function __construct() {
        $this->cors = false;
    }

    private array $routingArray = array();

    public function route($httpMethod, $url, $action, $json = true) {
        array_push($this->routingArray, array(
           "http_method" => $httpMethod,
           "url" => $url,
           "action" =>$action,
           "json" => $json
        ));
    }

    public function trueCors($allowedOrigin, $acah) {
        $this->cors = true;
        $this->allowedOrigin = $allowedOrigin;
        $this->acah = $acah;//access control allowed header
    }

    private function request_identification($httpMethod, $url, $params = array()) {
        
    }
}
