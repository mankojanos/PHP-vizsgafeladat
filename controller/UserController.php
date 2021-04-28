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
            if($this->userDBHandler->userValidate($username, filter_input(INPUT_POST, 'passwd', FILTER_SANITIZE_SPECIAL_CHARS))) {
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

    /**
     * Sign up user
     * GET request  => render the page
     * POST request => register user
     */
    public function signUp() {
        $user = new User();

        if(filter_input(INPUT_POST, 'username')) {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
            $user->setUsername($username);
            $user->setPassword(filter_input(INPUT_POST, 'passwd', FILTER_SANITIZE_SPECIAL_CHARS));
            try {
                $user->registrationValidation();
                if(!$this->userDBHandler->checkUsername($username)) {
                    $this->userDBHandler->save($user);
                    $this->view->setMessageSession('The user: ' . $user->getUsername() . ' is registered. Please Log in.');
                    $this->view->redirect('users', 'login');
                } else {
                    $errors = [];
                    $errors['username'] = 'This username was registered before';
                    $this->view->setVar('errors', $errors);
                }
            } catch (ValidationException $e) {
                $this->view->setVar('errors', $e->getErrors());
            }
        }
        $this->view->setVar('user', $user);
        $this->view->render('users', 'signUp');
    }

    public function logOut() {
        session_destroy();
        $this->view->redirect('users', 'logout');
    }
}
