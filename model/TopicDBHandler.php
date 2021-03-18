<?php
require_once __DIR__ . '/../global/PDOconnection.php';
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../model/Topic.php';
require_once __DIR__ . '/../model/Comment.php';


class TopicDBHandler {
    private PDO $db;

    /**
     * TopicDBHandler constructor.
     * @param PDO $db
     */
    public function __construct()
    {
        $this->db = PDOconnection::getCopy();
    }

    /**
     * Return all topic
     * @return array topic array WITHOUT comments
     * @throws PDOException if databes error
     */
    public function allTopic(): array {
        $query = $this->db->query("SELECT * FROM topics INNER JOIN users ON topics.author = users.username");
        $res = $query->fetchAll(PDO::FETCH_ASSOC);
        $topicArray = array();
        foreach ($res as $topic) {
            $author = new User($topic['username']);
            $topicArray[] = new Topic($topic['id'], $topic['title'], $topic['content'], $author);
        }
        return $topicArray;
    }

    /**
     * Search topic base on id WITHOUT comments
     * @param string $topicId topic id
     * @return Topic|null the topic, or null
     * @throws PDOException if database error
     */
    public function idBaseSearch(string $topicId): ?Topic {
        $query = $this->db->prepare("SELECT * FROM topics WHERE id=?");
        $query->execute(array($topicId));
        $topic = $query->fetch(PDO::FETCH_ASSOC);

        if ($topic != null) {
            return new Topic($topic['id'], $topic['title'], $topic['content'], new User($topic['author']));
        }
        return null;
    }

    /**
     * Search topic base on id WITH comments
     * @param string $topicId topic id
     * @return Topic|null the topic, or null
     * @throws PDOException if database error
     */
    public function idBaseSearchWithComment (string $topicId): ?Topic {
        $query = $this->db->prepare("SELECT T.id AS 'topic.id', T.title AS 'topic.title', T.content AS 'topic.content', T.author AS 'topic.author', C.id AS 'comment.id', C.content AS 'comment.content', C.topic AS 'comment.topic', C.author AS 'comment.author' FROM topics as T LEFT OUTER JOIN comments C on T.id = C.topic WHERE T.id=?");
        $query->execute(array($topicId));
        $topicWithComments = $query->fetchAll(PDO::FETCH_ASSOC);

        if(!empty($topicWithComments)) {
            $topic = new Topic($topicWithComments[0]['topic.id'], $topicWithComments[0]['topic.title'], $topicWithComments[0]['topic.content'], new User($topicWithComments[0]['topic.author']));
            $comments = array();
            if($topicWithComments[0]['comment.id'] != null) {
                foreach ($topicWithComments as $comment) {
                    $comment = new Comment($comment['comment.id'], $comment['comment.content'], new User($comment['comment.author']), $topic );
                    $comments[] = $comment;
                }
            }
            $topic->setComments($comments);
            return $topic;
        }
        return null;
    }
}
