<?php

include_once './ConnexionBD.php';
include_once './Media.php';

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
        
    public function getMediaId(): ?int
    {
        return parent::getId();
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
    
    public function setMediaId(int $mediaId)
    {
        parent::setId($mediaId);
    }

    
    public static function getMovieById(int $id){
        try{

            $connexion = connexion();
            $request = $connexion->prepare("SELECT movie.id, title, author, available, duration, genre, media_id FROM movie JOIN media ON media.id = movie.media_id WHERE movie.id=:id;");

            $request->bindParam(":id",$id);

            $request->execute();

            $movieInfo = $request->fetch(PDO::FETCH_ASSOC);

            if(!empty($movieInfo)){
                $genreObject = Genre::from($movieInfo["genre"]);
                $movieObject = new Movie($movieInfo['title'], $movieInfo['author'], $movieInfo['available'], $movieInfo['duration'], $genreObject, $movieInfo['media_id'], $movieInfo['id']);
            }else{
                $movieObject = null;
            }

            return $movieObject;

        }catch(PDOException $e){
            die('Movie Get By Id Error : '.$e);
        }
    }

    public static function getAllMovies(){
        try{
            $moviesList = [];

            $connexion = connexion();
            $request = $connexion->prepare("SELECT movie.id, title, author, available, duration, genre, media_id FROM movie JOIN media ON media.id = movie.media_id;");

            $request->execute();

            $moviesInfo = $request->fetchAll(PDO::FETCH_ASSOC);

            foreach($moviesInfo as $movieInfo){
                $genreObject = Genre::from($movieInfo["genre"]);
                $movieObject = new Movie($movieInfo['title'], $movieInfo['author'], $movieInfo['available'], $movieInfo['duration'], $genreObject, $movieInfo['media_id'], $movieInfo['id']);
                array_push($moviesList,$movieObject);
            }
            
            return $moviesList;

        }catch(PDOException $e){
            die('Movie Get All Error : '.$e);
        }
    }

    public function addNewMovie()
    {
        try{
            $connexion = connexion();
            $request = $connexion->prepare("INSERT INTO media (title, author, available) VALUES(:title, :author, :availble);");

            $title = $this->getTitle();
            $author = $this->getAuthor();
            $available = $this->getAvailable();
            $duration = $this->getDuration();
            $genre = $this->getGenre()->value;


            $request->bindParam(":title",$title);
            $request->bindParam(":author",$author);
            $request->bindParam(":availble",$available);

            $request->execute();

            $getIdInserted = $connexion->lastInsertId();
            $this->setMediaId($getIdInserted);
            $mediaId = $this->getMediaId();

            $request = $connexion->prepare("INSERT INTO movie (duration, genre, media_id) VALUES(:duration, :genre, :media_id);");

            $request->bindParam(":duration",$duration);
            $request->bindParam(":genre",$genre);
            $request->bindParam(":media_id",$mediaId);

            $request->execute();

            $getIdInserted = $connexion->lastInsertId();
            $this->setId($getIdInserted);

            return $this;

        }catch(PDOException $e){
            die('Movie Add Error : '.$e);
        }
    }

    public function updateBook(){
        try{
            $connexion = connexion();
            $request = $connexion->prepare("UPDATE media SET title=:title, author=:author, available=:available WHERE id=:mediaId;");

            $title = $this->getTitle();
            $author = $this->getAuthor();
            $available = $this->getAvailable();
            $duration = $this->getDuration();
            $genre = $this->getGenre();
            $mediaId = $this->getMediaId();

            $request->bindParam(":mediaId",$mediaId);
            $request->bindParam(":title",$title);
            $request->bindParam(":author",$author);
            $request->bindParam(":available",$available);

            $request->execute();

            $request = $connexion->prepare("UPDATE movie SET duration=:duration, genre=:genre  WHERE id=:id;");

            $request->bindParam(":id",$id);
            $request->bindParam(":duration",$duration);
            $request->bindParam(":genre",$genre);
            
            $request->execute();

            return $this;

        }catch(PDOException $e){
            die('Movie Update Error : '.$e);
        }
    }

    public function removeMovie(){
        try{
            $connexion = connexion();
            
            $id = $this->getId();
            $mediaId = $this->getMediaId();

            $request = $connexion->prepare("DELETE FROM movie WHERE id=:id;");

            $request->bindParam(":id",$id);
            
            $request->execute();

            $request = $connexion->prepare("DELETE FROM media WHERE id=:mediaId;");

            $request->bindParam(":mediaId",$mediaId);

            $request->execute();

            return true;

        }catch(PDOException $e){
            die('Movie Delete Current Object Error : '.$e);
        }
    }
}