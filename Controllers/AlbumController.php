<?php

/**
 * Controller AlbumController gérant les actions liées aux Albums
 * 
 * Ce controller contient la gestion des actions liées aux Albums :
 * 
 * All() => Affichage des Albums et gestion des filtres.
 * show(int $id) => Affiche les informations concernant un Album.
 * rendre(int $id) => Change le statut disponible de l'Album en disponible.
 * emprunter(int $id) => Change le statut disponible de l'Album en non disponible.
 * delete(int $id) => Retire l'Album de la base de donnée.
 * update(int $id) => Met à jour les informations d'un Album.
 * new() => Ajouter un nouvel Album en base de donnée.
 * 
 */
class AlbumController{
    
    /**
     * Affiche la liste des albums et gére le traitement du filtre.
     * 
     * Cette fonction recupére tout les albums et les affiche dans la vue.
     * Il gére aussi les filtres : titre, auteur, éditeur, disponible, et nb_de_titre.
     */
    public function All(): void
    {
        $albums = Album::getAllAlbums();

        if(isset($_POST['search']) && !empty($_POST['search'])){
            $filter = strtolower(htmlspecialchars($_POST['search']));
            $filteredAlbums = [];
            $filterdArray = array_filter($albums, function($album) use($filter){
                return stripos($album->getTitle(), $filter) !== false ||
                    stripos($album->getAuthor(), $filter) !== false ||
                    stripos($album->getEditor(), $filter) !== false;
                }
            );

            if(count($filterdArray) === 0){
                array_map(function($album) use(&$filter, &$filteredAlbums){
                    $words = [];
                    $title = $album->getTitle();
                    $author = $album->getAuthor();
                    $editor = $album->getEditor();
                    $titleWords = explode(' ', $title);
                    $authorWords = explode(' ', $author);
                    $editorWords = explode(' ', $editor);
                    $words = array_merge($titleWords, $authorWords, $editorWords);
                    $searchWords = explode(" ",$filter);
                    array_map(function($word) use($searchWords, $album, &$filteredAlbums){
                        array_map(function($searchWord) use($word,$album, &$filteredAlbums){
                            if(levenshtein($word, strtolower($searchWord)) <= 2){
                                if(!in_array($album,$filteredAlbums)){
                                    array_push($filteredAlbums,$album);
                                }
                            }
                        },$searchWords);
                    },$words);
                },$albums);
                $albums = $filteredAlbums;

            }else{
                $albums = $filterdArray;
            }
        }

        if(isset($_POST['sort']) && !empty($_POST['sort'])){
            $sortBy = $_POST['sort'] ?? 'title';
            usort($albums, function($a, $b) use ($sortBy) {
                switch ($sortBy) {
                    case 'title':
                        return strcmp(strtolower($a->getTitle()), strtolower($b->getTitle()));
                    case 'author':
                        return strcmp(strtolower($a->getAuthor()), strtolower($b->getAuthor()));
                    case 'track_number':
                        return $a->getTrackNumber() <=> $b->getTrackNumber();
                    case 'editor':
                        return strcmp(strtolower($a->getEditor()), strtolower($b->getEditor()));
                    case 'available':
                        return $a->getAvailable() <=> $b->getAvailable();
                    default:
                        return 0;
                }
            });
        }
        require_once('Views/albums/index.php');
    }

    /**
     * Supprime l'album de la base de donnée
     * 
     * Cette fonction supprime l'album et renvoie vers la liste des albums.
     * Si l'utilisateur n'est pas connecté renvoie vers la page de connexion.
     * Si l'album n'existe pas, renvoie vers une page 404 not found.
     * 
     * @param int $id L'identifiant de l'album.
     */
    public function delete(int $id): void
    {
        $album = Album::getAlbumById($id);
        if($album instanceof Album){
            if(isset($_SESSION['currentUser']) && !empty($_SESSION['currentUser'])){
                $album->removeAlbum();
                header("location:".BASE_URL."/album/All");
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
     * Change le statut disponibilité de l'album
     * 
     * Cette fonction change le statut disponibilité de l'album en disponible et renvoie vers la liste des albums.
     * Si l'utilisateur n'est pas connecté renvoie vers la page de connexion.
     * Si l'album n'existe pas, renvoie vers une page 404 not found.
     * 
     * @param int $id L'identifiant de l'album.
     */
    public function rendre(int $id){
        $album = Album::getAlbumById($id);
        if($album instanceof Album){
            if(isset($_SESSION['currentUser']) && !empty($_SESSION['currentUser'])){
                $album->rendre();
                $album->updateAlbum();
                header("location:".BASE_URL."/album/show/$id");
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
     * Change le statut disponibilité de l'album
     * 
     * Cette fonction change le statut disponibilité de l'album en non disponible et renvoie vers la liste des albums.
     * Si l'utilisateur n'est pas connecté renvoie vers la page de connexion.
     * Si l'album n'existe pas, renvoie vers une page 404 not found.
     * 
     * @param int $id L'identifiant de l'album.
     */
    public function emprunter(int $id){
        $album = Album::getAlbumById($id);
        if($album instanceof Album){
            if(isset($_SESSION['currentUser']) && !empty($_SESSION['currentUser'])){
                $album->emprunter();
                $album->updateAlbum();
                header("location:".BASE_URL."/album/show/$id");
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
     * Affiche les informations d'un album.
     * 
     * Cette fonction affiche la vue du détail d'un album et les boutons d'actions associés. 
     * 
     * @param int $id L'identifiant de l'album
     */
    public function show(int $id){
        $album = Album::getAlbumById($id);
        if($album instanceof Album){
            require_once('Views/albums/details_album.php');
        }else{
            require_once('Controllers/ErrorController.php');
            $controller = new ErrorController();
            $controller->noFoundError();
        }
    }

    /**
     * Ajoute un nouvel album dans la base de donnée.
     * 
     * Cette fonction ajoute un nouvel album et renvoie vers la liste des albums.
     * Si un ou plusieurs champ(s) est/sont invalide(s), ajoute l'erreur pour l'afficher dans la vue.
     * Si l'utilisateur n'est pas connecté, renvoie vers la page de connexion.
     * 
     */
    public function new(): void
    {
        $errors = [];
        if(isset($_SESSION['currentUser']) && !empty($_SESSION['currentUser'])){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if(isset($_POST['title']) && !empty($_POST['title']) && isset($_POST['author']) && !empty($_POST['author']) && isset($_POST['editor']) && !empty($_POST['editor']) && isset($_POST['available']) && ($_POST['available'] === '1' || $_POST['available'] === '0')){
                    $title = htmlspecialchars($_POST['title']);
                    $author = htmlspecialchars($_POST['author']);
                    $editor = htmlspecialchars($_POST['editor']);
                    $available = htmlspecialchars($_POST['available']);

                    $newAlbum = new Album($title, $author, $available, 0, $editor, [], null, null);
                    $newAlbum->addNewAlbum();

                    header("location:".BASE_URL."/album/All");

                }else{
                    array_push($errors,"Un ou plusieurs champ(s) vide(s) !");
                }
            }
        }else{
            header("location:".BASE_URL."/connexion");                
        }
        require_once('Views/albums/new_album.php');
    }

    /**
     * Met à jour les informations de l'album dans la base de donnée.
     * 
     * Cette fonction met à jour les informations de l'album et renvoie vers la liste des albums.
     * Si un ou plusieurs champ(s) est/sont invalide(s), ajoute l'erreur pour l'afficher dans la vue.
     * Si l'utilisateur n'est pas connecté, renvoie vers la page de connexion.
     * 
     * @param int $id L'identifiant de l'album.
     */
    public function update(int $id){
        $errors = [];
        $updatedAlbum = Album::getAlbumById($id);
        if($updatedAlbum instanceof Album){
            if(isset($_SESSION['currentUser']) && !empty($_SESSION['currentUser'])){
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if(isset($_POST['title']) && !empty($_POST['title']) && isset($_POST['author']) && !empty($_POST['author']) && isset($_POST['editor']) && !empty($_POST['editor']) && isset($_POST['available']) && ($_POST['available'] === '1' || $_POST['available'] === '0')){
                    $title = htmlspecialchars($_POST['title']);
                    $author = htmlspecialchars($_POST['author']);
                    $editor = htmlspecialchars($_POST['editor']);
                    $available = htmlspecialchars($_POST['available']);

                        $updatedAlbum->setTitle($title);
                        $updatedAlbum->setAuthor($author);
                        $updatedAlbum->setEditor($editor);
                        $updatedAlbum->setAvailable($available);

                        $updatedAlbum->updateAlbum();

                        header("location:".BASE_URL."/album/All");

                    }else{
                        array_push($errors,"Un ou plusieurs champ(s) vide(s) !");
                    }
                }
                require_once('Views/albums/update_album.php');
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