<?php

include_once './ConnexionBD.php';

class Media{

    private ?int $id;
    private string $title;
    private string $atuhor;
    private bool $available;

    public function __construct(string $title, string $atuhor, bool $available, ?int $id = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->atuhor = $atuhor;
        $this->available = $available;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getAuthor(): ?string
    {
        return $this->atuhor;
    }

    public function getAvailable(): ?bool
    {
        return $this->available;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function setAuthor(string $atuhor)
    {
        $this->atuhor = $atuhor;
    }

    public function setAvailable(bool $available)
    {
        $this->available = $available;
    }

    public function rendre()
    {
        if($this->getAvailable() === false){
            $this->setAvailable(true);
            return true;
        }else{
            return false;
        }
    }

    public function emprunter()
    {
        if($this->getAvailable() === true){
            $this->setAvailable(false);
            return true;
        }else{
            return false;
        }
    }

    public function getAllMedia(){
        try{
            $mediaList = [];

            $connexion = connexion();
            $request = $connexion->prepare("SELECT * FROM media;");

            $request->execute();

            $mediasInfo = $request->fetchAll(PDO::FETCH_ASSOC);

            foreach($mediasInfo as $mediaInfo){
                $mediaObject = new Media($mediaInfo['title'], $mediaInfo['author'], $mediaInfo['available'], $mediaInfo['id']);
                array_push($mediaList,$mediaObject);
            }
            
            return $mediaList;

        }catch(ErrorException $e ){
            die('Media Get All Media Error : '.$e);
        }
    }

    public function getMediaById(int $id){
        try{
            $connexion = connexion();
            $request = $connexion->prepare("SELECT * FROM media WHERE id=:id;");

            $request->bindParam(":id",$id);

            $request->execute();

            $mediaInfo = $request->fetchAll(PDO::FETCH_ASSOC);

            if(!empty($mediaInfo[0])){
                $mediaObject = new Media($mediaInfo[0]['title'], $mediaInfo[0]['author'], $mediaInfo[0]['available'], $mediaInfo[0]['id']);
            }else{
                $mediaObject = Null;
            }
            return $mediaObject;

        }catch(ErrorException $e ){
            die('Media Get Media By Id Error : '.$e);
        }
    }

    public function addNewMedia(Media $media){
        try{
            $connexion = connexion();
            $request = $connexion->prepare("INSERT INTO media (title, author, available) VALUES(:title, :author, :availble);");

            $title = $media->getTitle();
            $author = $media->getAuthor();
            $available = $media->getAvailable();

            $request->bindParam(":title",$title);
            $request->bindParam(":author",$author);
            $request->bindParam(":availble",$available);

            $request->execute();

            $getIdInserted = $connexion->lastInsertId();
            $media->setId($getIdInserted);
            return $media;

        }catch(ErrorException $e ){
            die('Media Add Error : '.$e);
        }
    }

    public function removeMedia(Media $media){
        try{
            $connexion = connexion();
            $request = $connexion->prepare("DELETE FROM media WHERE id=:id");

            $id = $media->getId();

            $request->bindParam(":id",$id);

            $request->execute();
            return true;

        }catch(ErrorException $e ){
            die('Media Remove Error : '.$e);
        }
    }

    public function updateMedia(Media $media){
        try{
            $connexion = connexion();
            $request = $connexion->prepare("UPDATE media SET title=:title, author=:author, available=:available WHERE id=:id;");

            $id = $media->getId();
            $title = $media->getTitle();
            $author = $media->getAuthor();
            $available = $media->getAvailable();

            $request->bindParam(":id",$id);
            $request->bindParam(":title",$title);
            $request->bindParam(":author",$author);
            $request->bindParam(":available",$available);

            $request->execute();
            return true;

        }catch(ErrorException $e ){
            die('Media Update Error : '.$e);
        }
    }
}

$media = new Media("test title","test author 2", true, 1);
var_dump($media->getAllMedia());