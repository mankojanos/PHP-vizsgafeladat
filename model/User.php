<?php
require_once __DIR__ . '/../global/ValidationException.php';

class User {
    private string $username;
    private string $password;

    public function __construct(string $username = '', string $password = '')
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function registrationValidation(): void {
        $errors = array();

        if(mb_strlen($this->username) < 5) {
            $errors['username'] = 'The username minimum length is 5 character';
        }
        if(mb_strlen($this->password) < 8) {
            $errors['password'] = 'The password minimum length is 8 character';
        }
        if(!empty($errors)) {
            throw new ValidationException($errors, 'Invalid user');
        }
    }
}
