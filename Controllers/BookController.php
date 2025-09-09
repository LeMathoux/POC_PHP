<?php

class BookController{
    
    public function All(): void
    {
        $books = Book::getAllBooks();

        if(isset($_POST['search']) && !empty($_POST['search'])){
            $filter = strtolower(htmlspecialchars($_POST['search']));
            $filteredBooks = [];
            $filterdArray = array_filter($books, function($book) use($filter){
                return stripos($book->getTitle(), $filter) !== false ||
                    stripos($book->getAuthor(), $filter) !== false;
                }
            );

            if(count($filterdArray) === 0){
                array_map(function($book) use(&$filter, &$filteredBooks){
                    $words = [];
                    $title = $book->getTitle();
                    $author = $book->getAuthor();
                    $titleWords = explode(' ', $title);
                    $authorWords = explode(' ', $author);
                    $words = array_merge($titleWords, $authorWords);
                    $searchWords = explode(" ",$filter);
                    array_map(function($word) use($searchWords, $book, &$filteredBooks){
                        array_map(function($searchWord) use($word,$book, &$filteredBooks){
                            if(levenshtein($word, strtolower($searchWord)) <= 2){
                                if(!in_array($book,$filteredBooks)){
                                    array_push($filteredBooks,$book);
                                }
                            }
                        },$searchWords);
                    },$words);
                },$books);
                $books = $filteredBooks;

            }else{
                $books = $filterdArray;
            }
        }

        if(isset($_POST['sort']) && !empty($_POST['sort'])){
            $sortBy = $_POST['sort'] ?? 'title';
            usort($books, function($a, $b) use ($sortBy) {
                switch ($sortBy) {
                    case 'title':
                        return strcmp(strtolower($a->getTitle()), strtolower($b->getTitle()));
                    case 'author':
                        return strcmp(strtolower($a->getAuthor()), strtolower($b->getAuthor()));
                    case 'page_number':
                        return $a->getPageNumber() <=> $b->getPageNumber();
                    default:
                        return 0;
                }
            });
        }
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

    public function delete(int $id): void
    {
        $book = Book::getBookById($id);
        $book->removeBook();
        header("loaction:".BASE_URL."/book/All");
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