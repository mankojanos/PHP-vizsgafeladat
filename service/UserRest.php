<?php
require_once __DIR__ . '/MainRest.php';

class UserRest extends MainRest {
    private $userDb;

    public function __construct()
    {
        $this->userDb = new UserDBHandler();
    }

    public function registration($data) {
        $user = new User($data->username, $data->passwd);
        try {
            $user->registrationValidation();
            $this->userDb->save($user);
            header($_SERVER['SERVER_PROTOCOL'] . ' 201 Done');
            header('Location: ' . $_SERVER['REQUEST_URI'] . '/' . $data->username);
        } catch (ValidationException $e) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo(json_encode($e->getErrors()));
        }
    }

    public function logIn($username) {
        $actualUser = parent::validateUser();
        if($actualUser->getUsername() != $username) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 402 Error');
        } else {
            header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
        }
    }
}

$userRest = new UserRest();
$route = Routing::getCopy();
$route->route('GET', '/user/$1', array($userRest, 'logIn'));
$route->route('POST', '/user', array($userRest, 'registration'));
