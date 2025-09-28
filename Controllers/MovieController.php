<?php

/**
 * Controller MovieController gérant les actions liées aux films
 * 
 * Ce controller contient la gestion des actions liées aux films :
 * 
 * All() => Affichage des films et gestion des filtres.
 * show(int $id) => Affiche les informations concernant un film.
 * rendre(int $id) => Change le statut disponible du films en disponible.
 * emprunter(int $id) => Change le statut disponible du film en non disponible.
 * delete(int $id) => Retire le film de la base de donnée.
 * update(int $id) => Met à jour les informations d'un film.
 * new() => Ajouter un nouveau film en base de donnée.
 * 
 */
class MovieController{

    /**
     * Ajoute un nouveau film dans la base de donnée.
     * 
     * Cette fonction ajoute un nouveau film et renvoie vers la liste des films.
     * Si un ou plusieurs champ(s) est/sont invalide(s), ajoute l'erreur pour l'afficher dans la vue.
     * Si l'utilisateur n'est pas connecté, renvoie vers la page de connexion.
     * 
     */
    public function new(): void
    {
        $errors = [];
        if(isset($_SESSION['currentUser']) && !empty($_SESSION['currentUser'])){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if(isset($_POST['title']) && !empty($_POST['title']) && isset($_POST['author']) && !empty($_POST['author']) && isset($_POST['genre']) && !empty($_POST['genre']) && isset($_POST['duration']) && !empty($_POST['duration']) && isset($_POST['available']) && ($_POST['available'] === '1' || $_POST['available'] === '0')){
                    $title = htmlspecialchars($_POST['title']);
                    $author = htmlspecialchars($_POST['author']);
                    $duration = htmlspecialchars($_POST['duration']);
                    $genre = htmlspecialchars($_POST['genre']);
                    $genreObject = Genre::from($genre);
                    $available = htmlspecialchars($_POST['available']);

                    $newMovie = new Movie($title, $author, $available, $duration, $genreObject, null, null);
                    $newMovie->addNewMovie();

                    header("location:".BASE_URL."/Movie/All");

                }else{
                    array_push($errors,"Un ou plusieurs champ(s) vide(s) !");
                }
            }
        }else{
            header("location:".BASE_URL."/connexion");                
        }
        require_once('Views/movies/new_movie.php');
    }

    /**
     * Met à jour les informations du film dans la base de donnée.
     * 
     * Cette fonction met à jour les informations du film et renvoie vers la liste des films.
     * Si un ou plusieurs champ(s) est/sont invalide(s), ajoute l'erreur pour l'afficher dans la vue.
     * Si l'utilisateur n'est pas connecté, renvoie vers la page de connexion.
     * 
     * @param int $id L'identifiant du film.
     */
    public function update(int $id){
        $errors = [];
        $updatedMovie = Movie::getMovieById($id);
        if($updatedMovie instanceof Movie){
            if(isset($_SESSION['currentUser']) && !empty($_SESSION['currentUser'])){
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if(isset($_POST['title']) && !empty($_POST['title']) && isset($_POST['author']) && !empty($_POST['author']) && isset($_POST['genre']) && !empty($_POST['genre']) && isset($_POST['duration']) && !empty($_POST['duration']) && isset($_POST['available']) && ($_POST['available'] === '1' || $_POST['available'] === '0')){
                        $title = htmlspecialchars($_POST['title']);
                        $author = htmlspecialchars($_POST['author']);
                        $duration = htmlspecialchars($_POST['duration']);
                        $genre = htmlspecialchars($_POST['genre']);
                        $genreObject = Genre::from($genre);
                        $available = htmlspecialchars($_POST['available']);

                        $updatedMovie->setTitle($title);
                        $updatedMovie->setAuthor($author);
                        $updatedMovie->setGenre($genreObject);
                        $updatedMovie->setDuration($duration);
                        $updatedMovie->setAvailable($available);

                        $updatedMovie->updateMovie();

                        header("location:".BASE_URL."/movie/All");

                    }else{
                        array_push($errors,"Un ou plusieurs champ(s) vide(s) !");
                    }
                }
                require_once('Views/movies/update_movie.php');
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
     * Affiche la liste des films et gére le traitement du filtre.
     * 
     * Cette fonction recupére tout les films et les affiche dans la vue.
     * Il gére aussi les filtres : titre, auteur, disponible, et la durée.
     */
    public function All(): void
    {
        $movies = Movie::getAllMovies();

        if(isset($_POST['search']) && !empty($_POST['search'])){
            $filter = strtolower(htmlspecialchars($_POST['search'],ENT_NOQUOTES));
            $filteredMovies = [];
            $filterdArray = array_filter($movies, function($movie) use($filter){
                return stripos($movie->getTitle(), $filter) !== false ||
                    stripos($movie->getAuthor(), $filter) !== false;
                }
            );

            if(count($filterdArray) === 0){
                array_map(function($movie) use(&$filter, &$filteredMovies){
                    $words = [];
                    $title = $movie->getTitle();
                    $author = $movie->getAuthor();
                    $titleWords = explode(' ', $title);
                    $authorWords = explode(' ', $author);
                    $words = array_merge($titleWords, $authorWords);
                    $searchWords = explode(" ",$filter);
                    array_map(function($word) use($searchWords, $movie, &$filteredMovies){
                        array_map(function($searchWord) use($word,$movie, &$filteredMovies){
                            if(levenshtein($word, strtolower($searchWord)) <= 2){
                                if(!in_array($movie,$filteredMovies)){
                                    array_push($filteredMovies,$movie);
                                }
                            }
                        },$searchWords);
                    },$words);
                },$movies);
                $movies = $filteredMovies;

            }else{
                $movies = $filterdArray;
            }
        }
        
        if(isset($_POST['sort']) && !empty($_POST['sort'])){
            $sortBy = $_POST['sort'] ?? 'title';
            usort($movies, function($a, $b) use ($sortBy) {
                switch ($sortBy) {
                    case 'title':
                        return strcmp(strtolower($a->getTitle()), strtolower($b->getTitle()));
                    case 'author':
                        return strcmp(strtolower($a->getAuthor()), strtolower($b->getAuthor()));
                    case 'genre':
                        return strcmp(strtolower($a->getGenre()->value), strtolower($b->getGenre()->value));
                    case 'duration':
                        return $a->getDuration() <=> $b->getDuration();
                    case 'available':
                        return $a->getAvailable() <=> $b->getAvailable();
                    default:
                        return 0;
                }
            });
        }
        require_once('Views/movies/index.php');
    }

    /**
     * Affiche les informations d'un film.
     * 
     * Cette fonction affiche la vue du détail d'un film et les boutons d'actions associés. 
     * 
     * @param int $id L'identifiant du film
     */
    public function show(int $id){
        $movie = Movie::getMovieById($id);
        if($movie instanceof Movie){
            require_once('Views/movies/details_movie.php');
        }else{
            require_once('Controllers/ErrorController.php');
            $controller = new ErrorController();
            $controller->noFoundError();
        }
    }

    /**
     * Change le statut disponibilité du film
     * 
     * Cette fonction change le statut disponibilité du film en disponible et renvoie vers la liste des films.
     * Si l'utilisateur n'est pas connecté renvoie vers la page de connexion.
     * Si l'album n'existe pas, renvoie vers une page 404 not found.
     * 
     * @param int $id L'identifiant du film.
     */
    public function rendre(int $id){
        $movie = Movie::getMovieById($id);
        if($movie instanceof Movie){
            if(isset($_SESSION['currentUser']) && !empty($_SESSION['currentUser'])){
                $movie->rendre();
                $movie->updateMovie();
                header("location:".BASE_URL."/movie/show/$id");
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
     * Change le statut disponibilité du film
     * 
     * Cette fonction change le statut disponibilité du film en non disponible et renvoie vers la liste des films.
     * Si l'utilisateur n'est pas connecté renvoie vers la page de connexion.
     * Si l'album n'existe pas, renvoie vers une page 404 not found.
     * 
     * @param int $id L'identifiant du film.
     */
    public function emprunter(int $id){
        $movie = Movie::getMovieById($id);
        if($movie instanceof Movie){
            if(isset($_SESSION['currentUser']) && !empty($_SESSION['currentUser'])){
                $movie->emprunter();
                $movie->updateMovie();
                header("location:".BASE_URL."/movie/show/$id");
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
     * Supprime le film de la base de donnée
     * 
     * Cette fonction supprime le film et renvoie vers la liste des films.
     * Si l'utilisateur n'est pas connecté renvoie vers la page de connexion.
     * Si l'album n'existe pas, renvoie vers une page 404 not found.
     * 
     * @param int $id L'identifiant du film.
     */
    public function delete(int $id): void
    {
        $movie = Movie::getMovieById($id);
        if($movie instanceof Movie){
            if(isset($_SESSION['currentUser']) && !empty($_SESSION['currentUser'])){
                $movie->removeMovie();
                header("location:".BASE_URL."/movie/All");
            }else{
                header("location:".BASE_URL."/connexion");
            }
        }else{
            require_once('Controllers/ErrorController.php');
            $controller = new ErrorController();
            $controller->noFoundError();
        }
    }
}