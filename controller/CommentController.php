<?php

require_once __DIR__ .'/../model/User.php';
require_once __DIR__ .'/../model/Topic.php';
require_once __DIR__ .'/../model/Comment.php';
require_once __DIR__ .'/../model/TopicDBHandler.php';
require_once __DIR__ .'/../model/CommentDBHandler.php';
require_once __DIR__ .'/../controller/MainController.php';

class CommentController  extends MainController {

    /**
     * Reference to comment DB
     * @var $commentDbHandler
     */
    private $CommentDbHandler;

    /**
     * Reference to topicDB
     * @var $TopicDBHandler
     */
    private $TopicDBHandler;

    public function __construct()
    {
        parent::__construct();
        $this->CommentDbHandler = new CommentDBHandler();
        $this->TopicDBHandler = new TopicDBHandler();
    }

    /**
     * Create comment to topic
     * POST request to create a comment
     *
     * The user (@link MainController::$actualUser}
     * @throws Exception if user is missing
     */
    public function commentCreate() {
        if(!isset($this->actualUser)) {
            throw new Exception('The user missing from session. Need to login to comment');
        }

        if(filter_input(INPUT_PUST, 'id')) {
            $topicId = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
            $topic = $this->TopicDBHandler->idBaseSearch($topicId);
            if($topic === null) {
                throw new Exception('topic with this id doesnt exists: ' . $topicId);
            }

            $comment = new Comment();
            $comment->setContent(filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS));
            $comment->setAuthor($this->actualUser);
            $comment->setTopic($topic);

            try {
                $comment->commentValidation();
                $this->CommentDbHandler->save($comment);
                $this->view->setMessageSession('Comment create successful');
                $this->view->redirect('topics', 'details');
            } catch (ValidationException $e) {
                $error = $e->getErrors();
                $this->view->setVar('errors', $error);
                $this->view->redirect('topics', 'details', 'id=' . $topic->getId());
            }
        } else {
            throw new Exception('Id is missing');
        }
    }
}
