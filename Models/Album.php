<?php

class Album extends Media{

    private ?int $id;
    private int $trackNumber;
    private string $editor;
    private array $songs;

    public function __construct(string $title, string $author, bool $available, int $trackNumber, string $editor, array $songs, ?int $mediaId = null, ?int $id = null)
    {
        parent::__construct($title, $author, $available, $mediaId);
        $this->id = $id;
        $this->trackNumber = $trackNumber;
        $this->editor = $editor;
        $this->songs = $songs;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSongNumber(): ?int
    {
        return $this->trackNumber;
    }

    public function getEditor(): ?string
    {
        return $this->editor;
    }

    public function getSongs(): array
    {
        return $this->songs;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setTrackNumber(int $trackNumber)
    {
        $this->trackNumber = $trackNumber;
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

    public function removeSong(Song $song): ?bool
    {
        if(in_array($song, $this->songs)){
            $this->songs = array_diff($this->songs,$song);
            return true;
        }else{
            return false;
        }
    }
}