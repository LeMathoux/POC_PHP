<?php

class Album extends Media{

    private ?int $id;
    private int $trackNumber;
    private string $editor;
    private array $songs;

    /**
     * Constructeur de la classe Album.
     * 
     * @param string $title
     * @param string $author
     * @param bool $available
     * @param int $trackNumber
     * @param string $editor
     * @param array $songs
     * @param int|null $mediaId
     * @param int|null $id
     * 
     */
    public function __construct(string $title, string $author, bool $available, int $trackNumber, string $editor, array $songs, ?int $mediaId = null, ?int $id = null)
    {
        parent::__construct($title, $author, $available, $mediaId);
        $this->id = $id;
        $this->trackNumber = $trackNumber;
        $this->editor = $editor;
        $this->songs = $songs;
    }

   /**
     * récupére l'id de l'album.
     * 
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
    
   /**
     * récupére l'id du media de l'album.
     * 
     * @return int|null
     */
    public function getMediaId(): ?int
    {
        return parent::getId();
    }

   /**
     * récupére le nombre de musique de l'album.
     * 
     * @return int
     */
    public function getTrackNumber(): int
    {
        return $this->trackNumber;
    }

   /**
     * récupére l'éditeur de l'album.
     * 
     * @return string
     */
    public function getEditor(): string
    {
        return $this->editor;
    }

    /**
     * récupére la liste des musiques de l'album.
     * 
     * @return array
     */
    public function getSongs(): array
    {
        return $this->songs;
    }

    /**
     * Définit l'id de l'album.
     * 
     * @param int $id
     *
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Définit le nombre de musiques de l'album.
     * 
     * @param int $trackNumber
     *
     */
    public function setTrackNumber(int $trackNumber): void
    {
        $this->trackNumber = $trackNumber;
    }

    /**
     * Définit l'éditeur de l'album.
     * 
     * @param string $editor
     *
     */
    public function setEditor(string $editor): void
    {
        $this->editor = $editor;
    }

    /**
     * Ajoute une musique dans la liste des musiques de l'album.
     * 
     * @param Song $song
     * @return bool
     *
     */
    public function addSong(Song $song): bool
    {
        if(!in_array($song, $this->songs)){
            array_push($this->songs, $song);
            $trackNumber = count($this->songs);
            $this->setTrackNumber($trackNumber);
            $this->updateAlbum();
            return true;
        }else{
            return false;
        }
    }

    /**
     * Récupére un album via son identifiant.
     * 
     * @param int $id
     * @return Album Objet Album
     */
    public static function getAlbumById(int $id){
        try{

            $connexion = connexion();
            $request = $connexion->prepare("SELECT album.id, title, author, editor, track_number, available, media_id FROM album JOIN media ON media.id = album.media_id WHERE album.id=:id;");

            $request->bindParam(":id",$id);

            $request->execute();

            $albumInfo = $request->fetch(PDO::FETCH_ASSOC);

            if(!empty($albumInfo)){
                $albumObject = new Album($albumInfo['title'], $albumInfo['author'], $albumInfo['available'], $albumInfo['track_number'],  $albumInfo['editor'], [], $albumInfo['media_id'], $albumInfo['id']);
                
                $albumId = $albumInfo['id'];
                $request = $connexion->prepare("SELECT * FROM song WHERE album_id=:id;");

                $request->bindParam(':id',$albumId);

                $request->execute();

                $songsInfos = $request->fetchAll(PDO::FETCH_ASSOC);

                foreach($songsInfos as $songsInfo){
                    $song = new Song($songsInfo['title'],$songsInfo['note'],$songsInfo['duration'],$songsInfo['album_id'],$songsInfo['id']);
                    $albumObject->addSong($song);
                }
            }else{
                $albumObject = null;
            }

            return $albumObject;

        }catch(PDOException $e){
            die('Album Get By Id Error : '.$e);
        }
    }

    /**
     * Retire une musique dans la liste des musiques de l'album.
     * 
     * @param Song $song
     * @return bool
     *
     */
    public function removeSong(Song $song): bool
    {
        if(in_array($song, $this->songs)){
            $this->songs = array_diff($this->songs,$song);
            $trackNumber = count($this->songs);
            $this->setTrackNumber($trackNumber);
            $this->updateAlbum();
            return true;
        }else{
            return false;
        }
    }

    /**
     * Ajoute l'album en base de donnée.
     * 
     * @return Album Objet Album
     */
    public function addNewAlbum(): Album{
        try{
            $connexion = connexion();
            $request = $connexion->prepare("INSERT INTO media (title, author, available) VALUES(:title, :author, :availble);");

            $title = $this->getTitle();
            $author = $this->getAuthor();
            $editor = $this->getEditor();
            $available = $this->getAvailable();
            $trackNumber = $this->getTrackNumber();


            $request->bindParam(":title",$title);
            $request->bindParam(":author",$author);
            $request->bindParam(":availble",$available);

            $request->execute();

            $getIdInserted = $connexion->lastInsertId();
            $this->setMediaId($getIdInserted);
            $mediaId = $this->getMediaId();

            $request = $connexion->prepare("INSERT INTO album (editor, track_number, media_id) VALUES(:editor, :track_number, :media_id);");

            $request->bindParam(":track_number",$trackNumber);
            $request->bindParam(":editor",$editor);
            $request->bindParam(":media_id",$mediaId);

            $request->execute();

            $getIdInserted = $connexion->lastInsertId();
            $this->setId($getIdInserted);

            return $this;

        }catch(PDOException $e){
            die('Album Add Error : '.$e);
        }
    }

    /**
     * Modifie l'album en base de donnée.
     * 
     * @return Album Objet Album
     */
    public function updateAlbum(): Album
    {
        try{
            $connexion = connexion();
            $request = $connexion->prepare("UPDATE media SET title=:title, author=:author, available=:available WHERE id=:mediaId;");

            $id = $this->getId();
            $mediaId = $this->getMediaId();
            $editor = $this->getEditor();
            $title = $this->getTitle();
            $author = $this->getAuthor();
            $available = $this->getAvailable();
            $trackNumber = $this->getTrackNumber();

            $request->bindParam(":mediaId",$mediaId);
            $request->bindParam(":title",$title);
            $request->bindParam(":author",$author);
            $request->bindParam(":available",$available);

            $request->execute();

            $request = $connexion->prepare("UPDATE album SET track_number=:track_number, editor=:editor WHERE id=:id;");

            $request->bindParam(":id",$id);
            $request->bindParam(":track_number",$trackNumber);
            $request->bindParam(":editor",$editor);
            
            $request->execute();

            return $this;

        }catch(PDOException $e){
            die('Album Update Error : '.$e);
        }
    }

    /**
     * Retire l'album de la base de donnée.
     * 
     * @return bool
     */
    public function removeAlbum(): bool
    {
        try{
            $connexion = connexion();
            
            $id = $this->getId();
            $mediaId = $this->getMediaId();

            $request = $connexion->prepare("DELETE FROM song WHERE album_id=:id;");

            $request->bindParam(":id",$id);
            
            $request->execute();

            $request = $connexion->prepare("DELETE FROM album WHERE id=:id;");

            $request->bindParam(":id",$id);
            
            $request->execute();

            $request = $connexion->prepare("DELETE FROM media WHERE id=:mediaId;");

            $request->bindParam(":mediaId",$mediaId);

            $request->execute();

            return true;

        }catch(PDOException $e){
            die('Album Delete Current Object Error : '.$e);
        }
    }

    /**
     * Récupére l'ensemble des Albums de la base de donnée
     * 
     * @return array
     */
    public static function getAllAlbums(): array
    {
        try{
            $albumsList = [];

            $connexion = connexion();
            $request = $connexion->prepare("SELECT Album.id, title, author, available, track_number, editor, media_id FROM album JOIN media ON media.id = album.media_id;");

            $request->execute();

            $albumsInfos = $request->fetchAll(PDO::FETCH_ASSOC);

            foreach($albumsInfos as $albumsInfo){
                $albumId = $albumsInfo['id'];
                $request = $connexion->prepare("SELECT * FROM song WHERE album_id=:id;");

                $request->bindParam(':id',$albumId);

                $request->execute();

                $songsInfos = $request->fetchAll(PDO::FETCH_ASSOC);

                $album = new Album($albumsInfo['title'], $albumsInfo['author'], $albumsInfo['available'], $albumsInfo['track_number'], $albumsInfo['editor'],[],$albumsInfo['media_id'],$albumsInfo['id']);

                foreach($songsInfos as $songsInfo){
                    $song = new Song($songsInfo['title'],$songsInfo['note'],$songsInfo['duration'],$songsInfo['album_id'],$songsInfo['id']);
                    $album->addSong($song);
                }
                array_push($albumsList,$album);
            }
            return $albumsList;

        }catch(PDOException $e){
            die('Album Get All Error : '.$e);
        }
    }
}