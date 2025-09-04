<?php

class Book extends Media{

    private int $pageNumber;

    public function __construct(int $id, string $titre, string $auteur, bool $disponible, int $pageNumber)
    {
        parent::__construct($id, $titre, $auteur, $disponible);
        $this->pageNumber = $pageNumber;
    }

    public function getPageNumber(): ?int
    {
        return $this->pageNumber;
    }

    public function setPageNumber(int $pageNumber)
    {
        $this->pageNumber = $pageNumber;
    }
}