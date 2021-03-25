<?php
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../model/UserDBHandler.php';

class MainRest {

    public function validateUser() {
        if(!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW_Authenticate: Basic realme="Authenticate is failed"');
            header($_SERVER['SERVER_PROTOCOL'] . '401 Authenticate error');
            die();
        } else {
            $userDB = new UserDBHandler();
            if($userDB->userValidate($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
                return new User($_SERVER['PHP_AUTH_USER']);
            } else {
                header('WWW_Authenticate: Basic realme="Authenticate is failed"');
                header($_SERVER['SERVER_PROTOCOL'] . '401 Authenticate error');
                die();
            }
        }
    }
}
