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
            $topicArray[] = new Topic($topic['id'], $topic['title'], $topic['value'], $author);
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
            return new Topic($topic['id'], $topic['title'], $topic['value'], new User($topic['author']));
        }
        return null;
    }


}
