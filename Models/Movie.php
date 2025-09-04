<?php

enum Genre:string{
    case Policier = "Policier";
    case Animation = "Animation";
    case Aventure = "Aventure";
}

class Movie extends Media{

    private float $duration;
    private Genre $genre;

    public function __construct(int $id, string $titre, string $auteur, bool $disponible, float $duration, Genre $genre)
    {
        parent::__construct($id, $titre,$auteur,$disponible);
        $this->duration = $duration;
        $this->genre = $genre;
    }

    public function getDuration(): ?float
    {
        return $this->duration;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setDuration(float $duration)
    {
        $this->duration = $duration;
    }

    public function setGenre(Genre $genre){
        $this->genre = $genre;
    }
}