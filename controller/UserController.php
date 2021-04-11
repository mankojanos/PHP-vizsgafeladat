<?php
require_once __DIR__ . '/../global/ViewHandler.php';
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../model/UserDBHandler.php';
require_once __DIR__ . '/../controller/MainController.php';

class UserController extends MainController {

    /**
     * User database handler
     * @var UserDBHandler
     */
    private $userDBHandler;

    public function __construct() {
        parent::__construct();
        $this->userDBHandler = new UserDBHandler();
        $this->view->setLayout('index');
    }

    /**
     * Validate User
     * GET request  => Login form render
     * POST request => Login, after validate
     */
    public function login() {
        if(filter_input(INPUT_POST, 'username')) {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
            if($this->userDBHandler->userValidate($username, filter_input(INPUT_POST, 'passw', FILTER_SANITIZE_SPECIAL_CHARS))) {
                $_SESSION['actual_user'] = $username;
                $this->view->redirect('posts', 'index');
            } else {
                $errors = array();
                $errors['username'] = 'Wrong user';
                $this->view->setVar('errors', $errors);
            }
        }
        $this->view->render('users', 'login');
    }

    
}
