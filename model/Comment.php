<?php
require_once __DIR__ . '/../global/ValidationException.php';

class Comment {
    private string $id;
    private string $value;
    private ?User $author;
    private ?Topic $topic;

    /**
     * Comment constructor.
     * @param string $id
     * @param string $value
     * @param ?User $author
     * @param ?Topic $topic
     */
    public function __construct(string $id, string $value, ?User $author = null, ?Topic $topic = null)
    {
        $this->id = $id;
        $this->value = $value;
        $this->author = $author;
        $this->topic = $topic;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return User|null
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @param User|null $author
     */
    public function setAuthor(?User $author): void
    {
        $this->author = $author;
    }

    /**
     * @return Topic|null
     */
    public function getTopic(): ?Topic
    {
        return $this->topic;
    }

    /**
     * @param Topic|null $topic
     */
    public function setTopic(?Topic $topic): void
    {
        $this->topic = $topic;
    }

    /**
     * check comment
     * @throws ValidationException if comment is invalid
     */
    public function commentValidation(): void {
        $errors = array();
        if(mb_strlen(trim($this->getValue())) < 5) {
            $errors['value'] = 'The comment is required. Minimum length is 5 character';
        }
        if($this->author == null) {
            $errors['author'] = 'The author is required';
        }
        if($this->topic == null) {
            $errors['topic'] = 'The topic is required';
        }
        if(!empty($errors)) {
            throw new ValidationException($errors, 'The comment is invalid');
        }
    }
}
