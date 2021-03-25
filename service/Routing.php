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
        $path = substr($_SERVER['REQUEST_URI'], strlen($_SERVER['PHP_SELF']) - strlen(basename($_SERVER['PHP_SELF'])) - 1);
        $path = parse_url($path)['path'];
        if($_SERVER['REQUEST_METHOD'] !== strtoupper($httpMethod) && ($this->cors == false || $_SERVER['REQUEST_METHOD'] != 'OPTION')) {
            return false;
        }

        $pathArray = explode('/', $path);
        $urlArray = explode('/', $url);

        if (count($pathArray) !== count($urlArray)) {
            return false;
        }

        $length = count($pathArray);

        for($i=0; $i < $length; $i++) {
            if($pathArray[$i] !== $urlArray[$i]) {
                if(preg_match('/\$([0-9]+?)/', $urlArray[$i]) !== 1) {
                    return false;
                }
            }
        }
        return true;
    }

    public function routingRequestProcessing() {

        $supportedMethods = array();

        foreach ($this->routingArray as $route) {

            $params = array();

            if($this->request_identification($route['http_method'], $route['url'], $params)) {
                if($this->cors == true && $_SERVER['REQUEST_METHOD'] === 'OPTION') {
                    array_push($supportedMethods, strtoupper($route['http_method']));
                } else {
                    if($route['json'] && isset($_SERVER['Content-Type']) && strpos($_SERVER['Content-Type'], 'application/json') !== false) {
                        array_push($params, json_decode(file_get_contents('php://input')));
                    }
                    if($this->cors === true) {
                        header('Acces-Control-Allow-Origin:', $this->allowedOrigin);
                    }
                    call_user_func_array($route['action'], $params);
                    return true;
                }
            }
        }
        if($this->cors) {
            header('Acces-Control-Allow-Origin:', $this->allowedOrigin);
            header('Acces-Control-Allow-Headers:', $this->acah);
            header('Acces-Control-Allow-Methods:', implode(',', $supportedMethods . ',OPTION'));
            return true;
        }
        return false;
    }


}
