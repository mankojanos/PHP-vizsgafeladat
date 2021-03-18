<?php
require_once __DIR__ . '/../global/ValidationException.php';
class Topic {
    private string $id;
    private string $title;
    private string $content;
    private ?User $author;
    private ?array $comments;

    /**
     * Topic constructor.
     * @param string $id
     * @param string $title
     * @param string $content
     * @param User|null $author
     * @param array|null $comments
     */
    public function __construct(string $id = '', string $title = '', string $content = '', ?User $author = null, ?array $comments = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->author = $author;
        $this->comments = $comments;
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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
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
     * @return array|null
     */
    public function getComments(): ?array
    {
        return $this->comments;
    }

    /**
     * @param array|null $comments
     */
    public function setComments(?array $comments): void
    {
        $this->comments = $comments;
    }

    /**
     * Check topic is valid
     * @throws ValidationException If topic is invalid
     */
    public function topicValidate(): void {
        $errors = array();

        if(mb_strlen(trim($this->title)) < 10 ){
            $errors['title'] = 'Title is require. Minimum length is 10 character';
        }

        if(mb_strlen(trim($this->content)) < 10) {
            $errors['content'] = 'Please add some text. Minimum length is 10 character';
        }

        if(!empty($errors)) {
            throw new ValidationException($errors, 'The topic is invalid');
        }
    }
}
