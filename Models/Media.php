<?php

class Media{

    private int $id;
    private string $titre;
    private string $auteur;
    private bool $disponible;

    public function __construct(int $id, string $titre, string $auteur, bool $disponible)
    {
        $this->id = $id;
        $this->titre = $titre;
        $this->auteur = $auteur;
        $this->disponible = $disponible;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    public function getDisponible(): ?bool
    {
        return $this->disponible;
    }

    public function setTitre(string $titre)
    {
        $this->titre = $titre;
    }

    public function setAuteur(string $auteur)
    {
        $this->auteur = $auteur;
    }

    public function setDisponible(bool $disponible)
    {
        $this->disponible = $disponible;
    }

    public function rendre()
    {
        if($this->getDisponible() === false){
            $this->setDisponible(true);
            return true;
        }else{
            return false;
        }
    }

    public function emprunter()
    {
        if($this->getDisponible() === true){
            $this->setDisponible(false);
            return true;
        }else{
            return false;
        }
    }
}