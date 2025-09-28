<?php

class Song{
    
    private ?int $id;
    private string $title;
    private int $note;
    private int $duration;
    private ?int $albumId;

    /**
     * Constructeur de la classe Song.
     * 
     * @param string $title
     * @param int $note
     * @param int $duration
     * @param int|null $albumId
     * @param \Datetime|null $updated_at
     * @param int|null $id
     * 
     */
    public function __construct(string $title, int $note, int $duration, ?int $albumId = null, ?int $id = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->note = $note;
        $this->duration = $duration;
        $this->albumId =$albumId;
    }

    /**
     * récupére l'id de la musique
     * 
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * récupére le titre de la musique
     * 
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * récupére la note de la musique
     * 
     * @return int
     */
    public function getNote(): int
    {
        return $this->note;
    }

    /**
     * récupére la durée de la musique
     * 
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * récupére l'id de l'album de la musique
     * 
     * @return int|null
     */
    public function getAlbumId(): ?int
    {
        return $this->albumId;
    }

    /**
     * Définit l'id de la musique
     * 
     * @param int $id
     *
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * Définit le titre de la musique
     * 
     * @param string $title
     *
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * Définit la note de la musique
     * 
     * @param int $note
     *
     */
    public function setNote(int $note)
    {
        $this->note = $note;
    }

    /**
     * Définit la durée de la musique
     * 
     * @param int $duration
     *
     */
    public function setDuration(int $duration)
    {
        $this->duration = $duration;
    }

    /**
     * Définit l'id de l'album de la musique
     * 
     * @param int $albumId
     *
     */
    public function setAlbumId(int $albumId)
    {
        $this->albumId = $albumId;
    }

    /**
     * récupére l'ensemble des musiques
     * 
     * @return array
     */
    public static function getAllSong() :array
    {
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
        }
    }

    /**
     * récupére une musique
     * 
     * @param int $id L'identifiant de la musique
     * @return Song Objet musique
     */
    public static function getSongById(int $id): Song
    {
        try{
            $connexion = connexion();
            $request = $connexion->prepare("SELECT * FROM song WHERE id=:id;");

            $request->bindParam(":id",$id);

            $request->execute();

            $songInfo = $request->fetch(PDO::FETCH_ASSOC);

            if(!empty($songInfo)){
                $songObject = new Song($songInfo['title'], $songInfo['note'], $songInfo['duration'], $songInfo['album_id'], $songInfo['id']);
            }else{
                $songObject = Null;
            }
            return $songObject;
        }catch(PDOException $e){
            die('Media Get Media By Id Error : '.$e);
        }
    }

    /**
     * Ajoute la musique en base de donnée.
     * 
     * @return Song Objet musique
     */
    public function addNewSong(): Song
    {
        try{
            $connexion = connexion();
            $request = $connexion->prepare("INSERT INTO song (title, note, duration, album_id) VALUES(:title, :note, :duration, :albumId);");

            $title = $this->getTitle();
            $note = $this->getNote();
            $duration = $this->getDuration();
            $albumId = $this->getAlbumId();

            $request->bindParam(":title",$title);
            $request->bindParam(":note",$note);
            $request->bindParam(":duration",$duration);
            $request->bindParam(":albumId",$albumId);

            $request->execute();

            $getIdInserted = $connexion->lastInsertId();
            $this->setId($getIdInserted);
            return $this;
            
        }catch(PDOException $e){
            die('Media Add Error : '.$e);
        }
    }

    /**
     * supprimer la musique de la base de donnée.
     * 
     * @return boolean
     */
    public function removeSong(): bool
    {
        try{
            $connexion = connexion();
            $request = $connexion->prepare("DELETE FROM song WHERE id=:id");

            $id = $this->getId();

            $request->bindParam(":id",$id);

            $request->execute();

            return true;
        }catch(PDOException $e){
            die('Media Remove Error : '.$e);
        }
    }

    /**
     * modifie la musique dans la base de donnée.
     * 
     * @return boolean
     */
    public function updateSong(): bool
    {
        try{
            $connexion = connexion();
            $request = $connexion->prepare("UPDATE song SET title=:title, note=:note, duration=:duration, album_id=:albumId WHERE id=:id;");

            $id = $this->getId();
            $title = $this->getTitle();
            $note = $this->getNote();
            $duration = $this->getDuration();
            $albumId = $this->getAlbumId();

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