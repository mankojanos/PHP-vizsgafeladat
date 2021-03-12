<?php
require_once __DIR__ . '/../global/PDOconnection.php';
require_once __DIR__ . '/../model/Comment.php';

class CommentDBHandler {
    private PDO $db;

    /**
     * CommentDBHandler constructor.
     */
    public function __construct()
    {
        $this->db = PDOconnection::getCopy();
    }

    /**
     * Save comment to database
     * @param Comment $comment comment to save
     * @return int ID of comment
     * @throws PDOException if database error
     */
    public function save(Comment $comment): int {
        $query = $this->db->prepare("INSERT INTO comments(value, author, topic) VALUES(?, ?, ?)");
        $query->execute(array($comment->getValue(), $comment->getAuthor()->getUsername(), $comment->getTopic()->getId()));
        return $this->db->lastInsertId();
    }
}
