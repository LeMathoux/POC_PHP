<?php

include_once './ConnexionBD.php';

class Book extends Media{

    private ?int $id;
    private int $pageNumber;

    public function __construct(string $title, string $author, bool $available, int $pageNumber, ?int $mediaId = null, ?int $id = null)
    {
        parent::__construct($title, $author, $available, $mediaId);
        $this->id = $id;
        $this->pageNumber = $pageNumber;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPageNumber(): ?int
    {
        return $this->pageNumber;
    }
    
    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setPageNumber(int $pageNumber)
    {
        $this->pageNumber = $pageNumber;
    }
}