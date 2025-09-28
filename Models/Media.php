<?php

abstract class Media{

    private ?int $id;
    private string $title;
    private string $atuhor;
    private bool $available;

    /**
     * Constructeur de la classe Media.
     * 
     * @param string $title
     * @param string $author
     * @param bool $available
     * @param int|null $id
     * 
     */
    public function __construct(string $title, string $atuhor, bool $available, ?int $id = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->atuhor = $atuhor;
        $this->available = $available;
    }

    /**
     * récupére l'id du media.
     * 
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * récupére le titre du media.
     * 
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * récupére l'auteur du media.
     * 
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->atuhor;
    }

    /**
     * récupére la disponibilité du media.
     * 
     * @return bool
     */
    public function getAvailable(): bool
    {
        return $this->available;
    }

    /**
     * Définit l'id du media.
     * 
     * @param string $title
     *
     */
    public function setMediaId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Définit le titre du media.
     * 
     * @param string $title
     *
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Définit l'auteur du media.
     * 
     * @param string $atuhor
     *
     */
    public function setAuthor(string $atuhor): void
    {
        $this->atuhor = $atuhor;
    }

    /**
     * Définit la disponibilité du media.
     * 
     * @param bool $available
     *
     */
    public function setAvailable(bool $available): void
    {
        $this->available = $available;
    }

    /**
     * Définit la disponibilité du media en true.
     * 
     * @param bool $available
     *
     */
    public function rendre(): bool
    {
        if($this->getAvailable() === false){
            $this->setAvailable(true);
            return true;
        }else{
            return false;
        }
    }

    /**
     * Définit la disponibilité du media en false.
     * 
     * @param bool $available
     *
     */
    public function emprunter(): bool
    {
        if($this->getAvailable() === true){
            $this->setAvailable(false);
            return true;
        }else{
            return false;
        }
    }
}