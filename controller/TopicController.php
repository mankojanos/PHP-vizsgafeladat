<?php
require_once __DIR__ . '/../model/Comment.php';
require_once __DIR__ . '/../model/Topic.php';
require_once __DIR__ . '/../model/TopicDBHandler.php';
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../global/ViewHandler.php';
require_once __DIR__ . '/../controller/MainController.php';

class TopicController extends MainController {
    /**
     * Topic database handler
     * @var $topicDbHandler
     */
    private $topicDbHandler;

    public function __construct()
    {
        parent::__construct();
        $this->topicDbHandler = new TopicDBHandler();
    }

    /**
     * Topic listing
     * Get all topic from database
     */
    public function index() {
        $topics = $this->topicDbHandler->allTopic();
        $this->view->setVar('topics', $topics);
        $this->view->render('topics', 'index');
    }

    /**
     * After GET request show one topic
     * @throws Exception if topic doesnt exists
     */
    public function topicDetails() {
        if(!filter_input(INPUT_GET, 'id')) {
            throw new Exception('ID is required');
        }
        $topicId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
        $topic = $this->topicDbHandler->idBaseSearchWithComment($topicId);
        if($topic === null) {
            throw new Exception('This ID is not valid, topic doesnt exists! ID: ' . $topicId);
        }
            $this->view->setVar('topic', $topic);
            $comment = $this->view->getVar('comment');
            $this->view->setVar('comment', ($comment === null) ? new Comment() : $comment);
            $this->view->render('topics', 'details');
    }

    /**
     * Create new topic
     * GET request  => render create page
     * POST request => save new topic to database
     * @throws Exception if error, orr user is missing from session
     */
    public function topicCreate() {
        if(!isset($this->actualUser)) {
            throw new Exception('The user missing from session. Need to login');
        }

        $topic = new Topic();
        if(filter_input(INPUT_POST, 'submit')) {
            $topic->setTitle(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS));
            $topic->setContent(filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS));
            $topic->setAuthor($this->actualUser);
            try{
                $topic->topicValidate();
                $this->topicDbHandler->save($topic);
                $this->view->setMessageSession('Topic save is successful');
                $this->view->redirect('topics', 'index');
            } catch (ValidationException $e) {
                $this->view->setVar('errors', $e->getErrors());
            }
        }
        $this->view->setVar('topic', $topic);
        $this->view->render('topica', 'topicCreat');
    }

    /**
     * Topic editing
     * GET request  => render edit page
     * POST request => update in database
     */
    public function editTopic() {

    }
}

