<?php
require_once __DIR__ . '/../global/ViewHandler.php';
require_once __DIR__ . '/../model/User.php';

class MainController {

    /**
     * view handler copy
     * @var ViewHandler
     */
    protected $view;

    /**
     * User copy
     * @var User
     */
    protected $actualUser;

    public function __construct() {
        $this->view = ViewHandler::getCopy();
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if(isset($_SESSION['actual_user'])) {
            $this->actualUser = new User($_SESSION['actual_user']);
            $this->view->setVar('actual_user', $this->actualUser->getUsername());
        }
    }
}
