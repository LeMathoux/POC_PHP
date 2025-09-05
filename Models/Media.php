<?php

abstract class Media{

    private ?int $id;
    private string $title;
    private string $atuhor;
    private bool $available;

    public function __construct(string $title, string $atuhor, bool $available, ?int $id = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->atuhor = $atuhor;
        $this->available = $available;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getAuthor(): ?string
    {
        return $this->atuhor;
    }

    public function getAvailable(): ?bool
    {
        return $this->available;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function setAuthor(string $atuhor)
    {
        $this->atuhor = $atuhor;
    }

    public function setAvailable(bool $available)
    {
        $this->available = $available;
    }

    public function rendre()
    {
        if($this->getAvailable() === false){
            $this->setAvailable(true);
            return true;
        }else{
            return false;
        }
    }

    public function emprunter()
    {
        if($this->getAvailable() === true){
            $this->setAvailable(false);
            return true;
        }else{
            return false;
        }
    }
}