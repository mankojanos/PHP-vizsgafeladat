<?php
require_once __DIR__ . '/../global/PDOconnection.php';

class UserDBHandler {
    private PDO $db;

    public function __construct() {
        $this->db = PDOconnection::getCopy();
    }

    /**
     * Save user to database
     * @param User $user user for save
     * @throws PDOException if database error
     */
    public function save(User $user) {
        $query = $this->db->prepare('INSERT INTO users VALUES (?, ?)');
        $query->execute(array($user->getUsername(), $user->getPassword()));
    }

    /**
     * Check the username in database
     * @param string $userName username for check
     * @return bool true if username was found in database, false if it's not.
     */
    public function checkUsername (string $userName) {
        $query = $this->db->prepare("SELECT COUNT(username) FROM users WHERE username = ?");
        $query->execute(array($userName));
        if($query->fetchColumn() > 0) {
            return true;
        }
        return false;
    }

    /**
     * check the username and the password is the database
     * @param string $username username for check
     * @param string $password password for check
     * @return bool true if the user is valid, false if invalid
     */
    public function userValidate(string $username, string $password) {
        $query = $this->db->prepare("SELECT COUNT(username) FROM users WHERE username = ? AND passwd = ?");
        $query->execute(array($username, $password));

        if($query->fetchColumn() > 0) {
            return true;
        }
        return false;
    }
}
