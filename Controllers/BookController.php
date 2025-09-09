<?php

class BookController{
    
    public function All(): void
    {
        require_once('Views/books/index.php');
    }

    public function show(int $id){
        $book = Book::getBookById($id);
        if($book instanceof Book){
            require_once('Views/books/details_book.php');
        }else{
            require_once('Controllers/ErrorController.php');
            $controller = new ErrorController();
            $controller->noFoundError();
        }
    }

    public function update(int $id){
        $errors = [];
        $updatedBook = Book::getBookById($id);
        if($updatedBook instanceof Book){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if(isset($_POST['title']) && !empty($_POST['title']) && isset($_POST['author']) && !empty($_POST['author']) && isset($_POST['page_number']) && !empty($_POST['page_number']) && isset($_POST['available']) && !empty($_POST['available'])){
                    $title = htmlspecialchars($_POST['title']);
                    $author = htmlspecialchars($_POST['author']);
                    $pageNumber = htmlspecialchars($_POST['page_number']);
                    $available = htmlspecialchars($_POST['available']);

                    $updatedBook->setTitle($title);
                    $updatedBook->setAuthor($author);
                    $updatedBook->setPageNumber($pageNumber);
                    $updatedBook->setAvailable($available);

                    $updatedBook->updateBook();

                    header("location:".BASE_URL."/book/All");

                }else{
                    array_push($errors,"Un ou plusieurs champ(s) vide(s) !");
                }
            }
            require_once('Views/books/update_book.php');
        }else{
            require_once('Controllers/ErrorController.php');
            $controller = new ErrorController();
            $controller->noFoundError();
        }
    }

    public function new(): void
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(isset($_POST['title']) && !empty($_POST['title']) && isset($_POST['author']) && !empty($_POST['author']) && isset($_POST['page_number']) && !empty($_POST['page_number']) && isset($_POST['available']) && !empty($_POST['available'])){
                $title = htmlspecialchars($_POST['title']);
                $author = htmlspecialchars($_POST['author']);
                $pageNumber = htmlspecialchars($_POST['page_number']);
                $available = htmlspecialchars($_POST['available']);

                $newBook = new Book($title, $author, $available, $pageNumber, null, null);
                $newBook->addNewBook();

                header("location:".BASE_URL."/book/All");

            }else{
                array_push($errors,"Un ou plusieurs champ(s) vide(s) !");
            }
        }
        require_once('Views/books/new_book.php');
    }
}