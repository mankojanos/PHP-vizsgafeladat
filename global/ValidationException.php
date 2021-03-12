<?php
class ValidationException extends Exception {
    private array $errors = array();

    public function __construct(array $errors, $msg = null)
    {
        parent::__construct($msg);
        $this->errors = $errors;
    }

    /**
     * Get all validation error
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
