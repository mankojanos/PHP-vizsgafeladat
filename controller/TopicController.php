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
        $this->view->render('topics', 'topicCreat');
    }

    /**
     * Topic editing
     * GET request  => render edit page
     * POST request => update in database
     *
     * @throws Exception if user is missing from session
     * @throws Exception If topic with this id doesnt exists
     * @throws Exception If id is missing
     * @throws Exception If user is not the original author
     */
    public function editTopic() {
        if(!isset($this->actualUser)) {
            throw new Exception('user missing from session. Login required');
        }

        if(!filter_input(INPUT_REQUEST, 'id', FILTER_SANITIZE_SPECIAL_CHARS)) {
            throw new Exception('topic id is required');
        }
        $topicId = filter_input(INPUT_REQUEST, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
        $topic = $this->topicDbHandler->idBaseSearch($topicId);

        if($topic === null) {
            throw new Exception('topic with this id doesnt exists: ' . $topicId);
        }

        if($topic->getAuthor() !== $this->actualUser) {
            throw new Exception('the actual user is not the original author');
        }

        if(filter_input(INPUT_POST, 'submit')) {
            $topic->setTitle(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS));
            $topic->setContent(filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS));
            try{
                $topic->topicValidate();
                $this->topicDbHandler->update($topic);
                $this->view->setMessageSession('topic is updated');
                $this->view->redirect('topics', 'index');
            } catch (ValidationException $e) {
                $this->view->setVar('errors', $e->getErrors());
            }
        }
        $this->view->setVar('topic', $topic);
        $this->view->render('topics',  'editTopic');
    }

    /**
     * Delete topic
     * This action use POST request
     *
     * @throws Exception If user is missing from session
     * @throws Exception If id is missing
     * @throws Exception If topic doesnt exits with this id
     * @throws Exception If topic author is not the actual user
     */
    public function deleteTopic() {
        if(!isset($this->actualUser)) {
            throw new Exception('user missing from session. Login required');
        }
        if(!filter_input(INPUT_REQUEST, 'id', FILTER_SANITIZE_SPECIAL_CHARS)) {
            throw new Exception('topic id is required');
        }
        $topicId = filter_input(INPUT_REQUEST, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
        $topic = $this->topicDbHandler->idBaseSearch($topicId);
        if($topic === null) {
            throw new Exception('topic with this id doesnt exists: ' . $topicId);
        }
        if($topic->getAuthor() !== $this->actualUser) {
            throw new Exception('the actual user is not the original author');
        }

        $this->topicDbHandler->delete($topic);
        $this->view->setMessageSession('Topic delete successful');
        $this->view->redirect('topics', 'index');
    }
}

