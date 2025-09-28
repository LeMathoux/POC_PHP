<?php

include_once('Models/Media.php');

enum Genre:string{
    case Policier = "Policier";
    case Animation = "Animation";
    case Aventure = "Aventure";
}

class Movie extends Media{

    private ?int $id;
    private float $duration;
    private Genre $genre;

    /**
     * Constructeur de la classe Movie.
     * 
     * @param string $title
     * @param string $author
     * @param bool $available
     * @param int $duration
     * @param Genre $genre
     * @param int|null $mediaId
     * @param int|null $id
     * 
     */
    public function __construct(string $title, string $author, bool $available, float $duration, Genre $genre, ?int $mediaId = null, ?int $id = null)
    {
        parent::__construct($title,$author,$available, $mediaId);
        $this->id = $id;
        $this->duration = $duration;
        $this->genre = $genre;
    }

    /**
     * récupére l'id du film
     * 
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * récupére la durée pour affichage
     * 
     * @return string
     */
    public function showDuration(): string
    {
        $duration = $this->getDuration();
        $hours = intdiv($duration, 60);
        $minutes = $duration%60;
        return "$hours heure(s) et $minutes minute(s)";
    }

    /**
     * récupére la durée pour affichage
     * 
     * @return float
     */
    public function getDuration(): float
    {
        return $this->duration;
    }

    /**
     * récupére le genre du film
     * 
     * @return Genre
     */
    public function getGenre(): Genre
    {
        return $this->genre;
    }
    
    /**
     * récupére l'identifiant du média du film
     * 
     * @return int|null
     */
    public function getMediaId(): ?int
    {
        return parent::getId();
    }

    /**
     * Définit l'id du film
     * 
     * @param int $id
     *
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Définit la durée du film
     * 
     * @param float $duration
     *
     */
    public function setDuration(float $duration): void
    {
        $this->duration = $duration;
    }

    /**
     * Définit le genre du film
     * 
     * @param Genre $genre
     *
     */
    public function setGenre(Genre $genre): void
    {
        $this->genre = $genre;
    }

    /**
     * récupére un film via son identifiant
     * 
     * @param int $id L'identifiant du film
     * @return Movie|null Objet film
     */
    public static function getMovieById(int $id): ?Movie
    {
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

    /**
     * récupére l'ensemble des films
     * 
     * @return array
     */
    public static function getAllMovies(): array
    {
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

    /**
     * Ajoute le film en base de donnée.
     * 
     * @return Movie Objet film
     */
    public function addNewMovie(): Movie
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

    /**
     * modifie le film dans la base de donnée.
     * 
     * @return Movie
     */
    public function updateMovie(): Movie
    {
        try{
            $connexion = connexion();
            $request = $connexion->prepare("UPDATE media SET title=:title, author=:author, available=:available WHERE id=:mediaId;");

            $title = $this->getTitle();
            $author = $this->getAuthor();
            $available = $this->getAvailable();
            $duration = $this->getDuration();
            $genre = $this->getGenre()->value;
            $mediaId = $this->getMediaId();
            $id = $this->getId();

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

    /**
     * supprime le film de la base de donnée.
     * 
     * @return boolean
     */
    public function removeMovie(): bool
    {
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