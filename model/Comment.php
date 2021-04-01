<?php
require_once __DIR__ . '/../global/ValidationException.php';

class Comment {
    private string $id;
    private string $content;
    private ?User $author;
    private ?Topic $topic;

    /**
     * Comment constructor.
     * @param string $id
     * @param string $content
     * @param ?User $author
     * @param ?Topic $topic
     */
    public function __construct(string $id = '', string $content = '', ?User $author = null, ?Topic $topic = null)
    {
        $this->id = $id;
        $this->content = $content;
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
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
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
        if(mb_strlen(trim($this->getContent())) < 5) {
            $errors['content'] = 'The comment is required. Minimum length is 5 character';
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
