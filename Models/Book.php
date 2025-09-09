<?php

include_once('Models/Media.php');

class Book extends Media{

    private ?int $id;
    private int $pageNumber;

    public function __construct(string $title, string $author, bool $available, int $pageNumber, ?int $mediaId = null, ?int $id = null)
    {
        parent::__construct($title, $author, $available, $mediaId);
        $this->id = $id;
        $this->pageNumber = $pageNumber;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPageNumber(): ?int
    {
        return $this->pageNumber;
    }
    
    public function getMediaId(): ?int
    {
        return parent::getId();
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setPageNumber(int $pageNumber)
    {
        $this->pageNumber = $pageNumber;
    }

    public function setMediaId(int $mediaId)
    {
        parent::setId($mediaId);
    }

    public static function getBookById(int $id){
        try{

            $connexion = connexion();
            $request = $connexion->prepare("SELECT book.id, title, author, available, page_number, media_id FROM book JOIN media ON media.id = book.media_id WHERE book.id=:id;");

            $request->bindParam(":id",$id);

            $request->execute();

            $bookInfo = $request->fetch(PDO::FETCH_ASSOC);

            if(!empty($bookInfo)){
                $bookObject = new Book($bookInfo['title'], $bookInfo['author'], $bookInfo['available'], $bookInfo['page_number'], $bookInfo['media_id'], $bookInfo['id']);
            }else{
                $bookObject = null;
            }

            return $bookObject;

        }catch(PDOException $e){
            die('Book Get By Id Error : '.$e);
        }
    }

    public static function getAllBooks(){
        try{
            $booksList = [];

            $connexion = connexion();
            $request = $connexion->prepare("SELECT book.id, title, author, available, page_number, media_id FROM book JOIN media ON media.id = book.media_id;");

            $request->execute();

            $booksInfo = $request->fetchAll(PDO::FETCH_ASSOC);

            foreach($booksInfo as $bookInfo){
                $bookObject = new Book($bookInfo['title'], $bookInfo['author'], $bookInfo['available'], $bookInfo['page_number'], $bookInfo['media_id'], $bookInfo['id']);
                array_push($booksList,$bookObject);
            }
            
            return $booksList;

        }catch(PDOException $e){
            die('Book Get All Error : '.$e);
        }
    }

    public function addNewBook()
    {
        try{
            $connexion = connexion();
            $request = $connexion->prepare("INSERT INTO media (title, author, available) VALUES(:title, :author, :availble);");

            $title = $this->getTitle();
            $author = $this->getAuthor();
            $available = $this->getAvailable();
            $pageNumber = $this->getPageNumber();


            $request->bindParam(":title",$title);
            $request->bindParam(":author",$author);
            $request->bindParam(":availble",$available);

            $request->execute();

            $getIdInserted = $connexion->lastInsertId();
            $this->setMediaId($getIdInserted);
            $mediaId = $this->getMediaId();

            $request = $connexion->prepare("INSERT INTO book (page_number, media_id) VALUES(:page_number, :media_id);");

            $request->bindParam(":page_number",$pageNumber);
            $request->bindParam(":media_id",$mediaId);

            $request->execute();

            $getIdInserted = $connexion->lastInsertId();
            $this->setId($getIdInserted);

            return $this;

        }catch(PDOException $e){
            die('Book Add Error : '.$e);
        }
    }

    public function updateBook(){
        try{
            $connexion = connexion();
            $request = $connexion->prepare("UPDATE media SET title=:title, author=:author, available=:available WHERE id=:mediaId;");

            $id = $this->getId();
            $mediaId = $this->getMediaId();
            $title = $this->getTitle();
            $author = $this->getAuthor();
            $available = $this->getAvailable();
            $pageNumber = $this->getPageNumber();

            $request->bindParam(":mediaId",$mediaId);
            $request->bindParam(":title",$title);
            $request->bindParam(":author",$author);
            $request->bindParam(":available",$available);

            $request->execute();

            $request = $connexion->prepare("UPDATE book SET page_number=:page_number WHERE id=:id;");

            $request->bindParam(":id",$id);
            $request->bindParam(":page_number",$pageNumber);
            
            $request->execute();

            return $this;

        }catch(PDOException $e){
            die('Book Update Error : '.$e);
        }
    }

    public function removeBook(){
        try{
            $connexion = connexion();
            
            $id = $this->getId();
            $mediaId = $this->getMediaId();

            $request = $connexion->prepare("DELETE FROM book WHERE id=:id;");

            $request->bindParam(":id",$id);
            
            $request->execute();

            $request = $connexion->prepare("DELETE FROM media WHERE id=:mediaId;");

            $request->bindParam(":mediaId",$mediaId);

            $request->execute();

            return true;

        }catch(PDOException $e){
            die('Book Delete Current Object Error : '.$e);
        }
    }
}