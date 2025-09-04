<?php

class Album extends Media{

    private int $songNumber;
    private string $editor;
    private array $songs;

    public function __construct(int $id, string $titre, string $auteur, bool $disponible, int $songNumber, string $editor, array $songs)
    {
        parent::__construct($id, $titre,$auteur,$disponible);
        $this->songNumber = $songNumber;
        $this->editor = $editor;
        $this->songs = $songs;
    }

    public function getSongNumber(): ?int
    {
        return $this->songNumber;
    }

    public function getEditor(): ?string
    {
        return $this->editor;
    }

    public function getSongs(): array
    {
        return $this->songs;
    }

    public function setSongNumber(int $songNumber)
    {
        $this->songNumber = $songNumber;
    }

    public function setEditor(string $editor)
    {
        $this->editor = $editor;
    }

    public function addSong(Song $song): ?bool
    {
        if(!in_array($song, $this->songs)){
            array_push($this->songs, $song);
            return true;
        }else{
            return false;
        }
    }

    public function removeSonge(Song $song): ?bool
    {
        if(in_array($song, $this->songs)){
            $this->songs = array_diff($this->songs,$song);
            return true;
        }else{
            return false;
        }
    }
}