<?php

include_once './ConnexionBD.php';

enum Genre:string{
    case Policier = "Policier";
    case Animation = "Animation";
    case Aventure = "Aventure";
}

class Movie extends Media{

    private ?int $id;
    private float $duration;
    private Genre $genre;

    public function __construct(string $title, string $author, bool $available, float $duration, Genre $genre, ?int $mediaId = null, ?int $id = null)
    {
        parent::__construct($title,$author,$available, $mediaId);
        $this->id = $id;
        $this->duration = $duration;
        $this->genre = $genre;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDuration(): ?float
    {
        return $this->duration;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setDuration(float $duration)
    {
        $this->duration = $duration;
    }

    public function setGenre(Genre $genre){
        $this->genre = $genre;
    }
}