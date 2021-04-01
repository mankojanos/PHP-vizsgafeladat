<?php
require_once __DIR__ . "/MainRest.php";
require_once __DIR__ . "/../model/Topic.php";
require_once __DIR__ . "/../model/TopicDBHandler.php";
require_once __DIR__ . "/../model/Comment.php";
require_once __DIR__ . "/../model/CommentDBHandler.php";

class TopicRest extends MainRest {
    private $topicDbHandler;
    private $commentDbHandler;

    public function __construct()
    {
        $this->topicDbHandler = new TopicDBHandler();
        $this->commentDbHandler = new CommentDBHandler();
    }

    public function getTopics() {
        $topics = $this->topicDbHandler->allTopic();

        $topics_array = array();

        /** @var Topic $topic */
        foreach ($topics as $topic) {
            $topics_array[] = array(
                'id'        => $topic->getId(),
                'title'     => $topic->getTitle(),
                'content'   => $topic->getContent(),
                'author'    => $topic->getAuthor()->getUsername()
            );
        }
        header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
        header('Content-Type: application/json');
        echo json_encode($topics_array);
    }

    public function topicCreate($data) {
        $currentUser = parent::validateUser();
        $topic = new Topic();

        if(isset($data->title) && isset($data->content)) {
            $topic->setTitle($data->title);
            $topic->setContent($data->content);
            $topic->setAuthor($currentUser);
        }

        try {
            $topic->topicValidate();
            $topicId = $this->topicDbHandler->save($topic);
            header($_SERVER['SERVER_PROTOCOL'] . '201 OK');
            header('Content-Type: application/json');
            header('Location: ' . $_SERVER['REQUEST_URI'] . '/' . $topicId);
            echo json_encode(array(
               'id'         =>$topicId,
               'title'      => $topic->getTitle(),
               'content'    => $topic->getContent()
            ));


        } catch (ValidationException $e) {
            header($_SERVER['SERVER_PROTOCOL'] . '400 Error');
            header('Content-Type: application/json');
            echo json_encode($e->getErrors());
        }
    }

    public function readOneTopic($topicId) {
        $topic = $this->topicDbHandler->idBaseSearchWithComment($topicId);
        if($topic === NULL) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 400 Error');
            return;
        }

        $topic_array = array(
            'id'        =>$topicId,
            'title'     => $topic->getTitle(),
            'content'   => $topic->getContent(),
            'author'    => $topic->getAuthor()->getUsername()
        );

        $topic_array['comments'] = array();
        /** @var Comment $comment */
        foreach ($topic->getComments() as $comment) {
            array_push($topic_array['comments'], array(
               'id'         =>$comment->getId(),
               'content'    =>$comment->getContent(),
               'author'     =>$comment->getAuthor()->getUsername()
            ));
        }

        header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
        header('Content-Type: application/json');
        echo json_encode($topic_array);
    }

    public function editTopic ($topicId, $data) {
        $currentUser = parent::validateUser();
        $topic = $this->topicDbHandler->idBaseSearch($topicId);
        if($topic === NULL) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 400 Error');
            return;
        }
        if($topic->getAuthor() !== $currentUser) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 403 Wrong user');
            return;
        }
        $topic->setTitle($data->title);
        $topic->setContent($data->content);

        try {
            $topic->topicValidate();
            $this->topicDbHandler->update($topic);
            header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
        } catch (ValidationException $e) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 400 Error');
            header('Content-Type: application/json');
            echo json_encode($e->getErrors());
        }
    }

    public function deleteTopic($topicId) {
        $currentUser = parent::validateUser();
        $topic = $this->topicDbHandler->idBaseSearch($topicId);
        if($topic === NULL) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 400 Error');
            return;
        }
        if($topic->getAuthor() !== $currentUser) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 403 Wrong user');
            return;
        }
        $this->topicDbHandler->delete($topic);
        header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
    }

    public function createComment($topicId, $data) {
        $currentUser = parent::validateUser();
        $topic = $topic = $this->topicDbHandler->idBaseSearch($topicId);
        if($topic === NULL) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 400 Error');
            return;
        }

        $comment = new Comment();
        $comment->setContent($data->content);
        $comment->setAuthor($data->author);
        $comment->setTopic($topic);
        try {
            $comment->commentValidation();
            $this->commentDbHandler->save($comment);
            header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
        } catch (ValidationException $e) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 400 Error');
            header('Content-Type: application/json');
            echo json_encode($e->getErrors());
        }
    }
}
