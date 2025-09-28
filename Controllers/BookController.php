<?php

/**
 * Controller BookController gérant les actions liées aux livres
 * 
 * Ce controller contient la gestion des actions liées aux livres :
 * 
 * All() => Affichage des livres et gestion des filtres.
 * show(int $id) => Affiche les informations concernant un livre.
 * rendre(int $id) => Change le statut disponible du livre en disponible.
 * emprunter(int $id) => Change le statut disponible du livre en non disponible.
 * delete(int $id) => Retire le livre de la base de donnée.
 * update(int $id) => Met à jour les informations d'un livre.
 * new() => Ajouter un nouveau livre en base de donnée.
 * 
 */
class BookController{
    
    /**
     * Affiche la liste des livres et gére le traitement du filtre.
     * 
     * Cette fonction recupére tout les livres et les affiche dans la vue.
     * Il gére aussi les filtres : titre, auteur, disponible, et nb_pages.
     */
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
                    case 'available':
                        return $a->getAvailable() <=> $b->getAvailable();
                    default:
                        return 0;
                }
            });
        }
        require_once('Views/books/index.php');
    }

    /**
     * Affiche les informations d'un livre.
     * 
     * Cette fonction affiche la vue du détail d'un livre et les boutons d'actions associés. 
     * 
     * @param int $id L'identifiant du livre
     */
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

    /**
     * Change le statut disponibilité du livre
     * 
     * Cette fonction change le statut disponibilité du livre en disponible et renvoie vers la liste des livres.
     * Si l'utilisateur n'est pas connecté renvoie vers la page de connexion.
     * Si le livre n'existe pas, renvoie vers une page 404 not found.
     * 
     * @param int $id L'identifiant du livre.
     */
    public function rendre(int $id){
        $book = Book::getBookById($id);
        if($book instanceof Book){
            if(isset($_SESSION['currentUser']) && !empty($_SESSION['currentUser'])){
                $book->rendre();
                $book->updateBook();
                header("location:".BASE_URL."/book/show/".$id);
            }else{
                header("location:".BASE_URL."/connexion");
            }
        }else{
            require_once('Controllers/ErrorController.php');
            $controller = new ErrorController();
            $controller->noFoundError();
        }
    }

    /**
     * Change le statut disponibilité du livre.
     * 
     * Cette fonction change le statut disponibilité du livre en non disponible et renvoie vers la liste des livres.
     * Si l'utilisateur n'est pas connecté renvoie vers la page de connexion.
     * Si le livre n'existe pas, renvoie vers une page 404 not found.
     * 
     * @param int $id L'identifiant du livre.
     */
    public function emprunter(int $id){
        $book = Book::getBookById($id);
        if($book instanceof Book){
            if(isset($_SESSION['currentUser']) && !empty($_SESSION['currentUser'])){
                $book->emprunter();
                $book->updateBook();
                require_once('Views/books/details_book.php');
            }else{
                header("location:".BASE_URL."/connexion");
            }
        }else{
            require_once('Controllers/ErrorController.php');
            $controller = new ErrorController();
            $controller->noFoundError();
        }
    }

    /**
     * Supprime le livre de la base de donnée
     * 
     * Cette fonction supprime le livre et renvoie vers la liste des livres.
     * Si l'utilisateur n'est pas connecté renvoie vers la page de connexion.
     * Si le livre n'existe pas, renvoie vers une page 404 not found.
     * 
     * @param int $id L'identifiant du livre.
     */
    public function delete(int $id): void
    {
        $book = Book::getBookById($id);
        if($book instanceof Book){
            if(isset($_SESSION['currentUser']) && !empty($_SESSION['currentUser'])){
                $book->removeBook();
                header("location:".BASE_URL."/book/All");
            }else{
                header("location:".BASE_URL."/connexion");
            }
        }else{
            require_once('Controllers/ErrorController.php');
            $controller = new ErrorController();
            $controller->noFoundError();
        }
    }

    /**
     * Met à jour les informations du livre dans la base de donnée.
     * 
     * Cette fonction met à jour les informations du livre et renvoie vers la liste des livres.
     * Si un ou plusieurs champ(s) est/sont invalide(s), ajoute l'erreur pour l'afficher dans la vue.
     * Si l'utilisateur n'est pas connecté, renvoie vers la page de connexion.
     * 
     * @param int $id L'identifiant du livre.
     */
    public function update(int $id){
        $errors = [];
        $updatedBook = Book::getBookById($id);
        if($updatedBook instanceof Book){
            if(isset($_SESSION['currentUser']) && !empty($_SESSION['currentUser'])){
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
                header("location:".BASE_URL."/connexion");              
            }
        }else{
            require_once('Controllers/ErrorController.php');
            $controller = new ErrorController();
            $controller->noFoundError();
        }
    }

    /**
     * Ajoute un nouveau livre dans la base de donnée.
     * 
     * Cette fonction ajoute un nouveau livre et renvoie vers la liste des livres.
     * Si un ou plusieurs champ(s) est/sont invalide(s), ajoute l'erreur pour l'afficher dans la vue.
     * Si l'utilisateur n'est pas connecté, renvoie vers la page de connexion.
     * 
     */
    public function new(): void
    {
        $errors = [];
        if(isset($_SESSION['currentUser']) && !empty($_SESSION['currentUser'])){
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
        }else{
            header("location:".BASE_URL."/connexion");
        }
    }
}