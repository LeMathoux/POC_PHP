<?php

class Song{
    
    private int $id;
    private string $titre;
    private int $note;
    private int $duree;

    public function __construct(int $id, string $titre, int $note, int $duree)
    {
        $this->id = $id;
        $this->titre = $titre;
        $this->note = $note;
        $this->duree = $duree;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setTitre(string $titre)
    {
        $this->titre = $titre;
    }

    public function setNote(int $note)
    {
        $this->note = $note;
    }

    public function setDuree(int $duree)
    {
        $this->duree = $duree;
    }
}