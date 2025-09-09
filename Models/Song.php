<?php

class Song{
    
    private ?int $id;
    private string $title;
    private int $note;
    private int $duration;
    private ?int $albumId;

    public function __construct(string $title, int $note, int $duration, ?int $albumId = null, ?int $id = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->note = $note;
        $this->duration = $duration;
        $this->albumId =$albumId;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }
    
    public function getAlbumId(): ?int
    {
        return $this->albumId;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function setNote(int $note)
    {
        $this->note = $note;
    }

    public function setDuration(int $duration)
    {
        $this->duration = $duration;
    }

    public function setAlbumId(int $albumId)
    {
        $this->id = $albumId;
    }

    public function getAllSong(){
        try{
            $songList = [];

            $connexion = connexion();
            $request = $connexion->prepare("SELECT * FROM song;");

            $request->execute();

            $songsInfo = $request->fetchAll(PDO::FETCH_ASSOC);

            foreach($songsInfo as $songInfo){
                $songObject = new Song($songInfo['title'], $songInfo['note'], $songInfo['duration'],$songInfo['album_id'], $songInfo['id']);
                array_push($songList,$songObject);
            }
            
            return $songList;

        }catch(ErrorException $e){
            die('Media Get All Media Error : '.$e);
            return null;
        }
    }

    public function getSongById(int $id){
        try{
            $connexion = connexion();
            $request = $connexion->prepare("SELECT * FROM song WHERE id=:id;");

            $request->bindParam(":id",$id);

            $request->execute();

            $songInfo = $request->fetchAll(PDO::FETCH_ASSOC);

            if(!empty($songInfo[0])){
                $songObject = new Song($songInfo[0]['title'], $songInfo[0]['note'], $songInfo[0]['duration'], $songInfo['album_id'], $songInfo[0]['id']);
            }else{
                $songObject = Null;
            }
            return $songObject;
        }catch(PDOException $e){
            die('Media Get Media By Id Error : '.$e);
        }
    }

    public function addNewSong(Song $song){
        try{
            $connexion = connexion();
            $request = $connexion->prepare("INSERT INTO song (title, note, duration, album_id) VALUES(:title, :note, :duration, :albumId);");

            $title = $song->getTitle();
            $note = $song->getNote();
            $duration = $song->getDuration();
            $albumId = $song->getAlbumId();

            $request->bindParam(":title",$title);
            $request->bindParam(":note",$note);
            $request->bindParam(":duration",$duration);
            $request->bindParam(":albumId",$albumId);

            $request->execute();

            $getIdInserted = $connexion->lastInsertId();
            $song->setId($getIdInserted);
            return $song;
            
        }catch(PDOException $e){
            die('Media Add Error : '.$e);
        }
    }

    public function removeSong(Song $song){
        try{
            $connexion = connexion();
            $request = $connexion->prepare("DELETE FROM song WHERE id=:id");

            $id = $song->getId();

            $request->bindParam(":id",$id);

            $request->execute();

            return true;
        }catch(PDOException $e){
            die('Media Remove Error : '.$e);
        }
    }

    public function updateSong(Song $song){
        try{
            $connexion = connexion();
            $request = $connexion->prepare("UPDATE song SET title=:title, note=:note, duration=:duration WHERE id=:id;");

            $id = $song->getId();
            $title = $song->getTitle();
            $note = $song->getNote();
            $duration = $song->getDuration();
            $albumId = $song->getAlbumId();

            $request->bindParam(":id",$id);
            $request->bindParam(":title",$title);
            $request->bindParam(":note",$note);
            $request->bindParam(":duration",$duration);
            $request->bindParam(":albumId",$albumId);

            $request->execute();
            return true;
        }catch(PDOException $e){
            die('Media Update Error : '.$e);
        }
    }
}