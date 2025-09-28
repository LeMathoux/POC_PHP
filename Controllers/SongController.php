<?php

/**
 * Controller SongController gérant les actions liées aux musiques
 * 
 * Ce controller contient la gestion des actions liées aux musiques :
 * 
 * All() => Affichage des musiques et gestion des filtres.
 * show(int $id) => Affiche les informations concernant une musique.
 * rendre(int $id) => Change le statut disponible d'une musique en disponible.
 * emprunter(int $id) => Change le statut disponible d'une musique en non disponible.
 * delete(int $id) => Retire la musique de la base de donnée.
 * update(int $id) => Met à jour les informations d'une musique.
 * new() => Ajouter une nouvelle musique en base de donnée.
 * 
 */
class SongController{
    
    /**
     * Affiche la liste des musiques et gére le traitement du filtre.
     * 
     * Cette fonction recupére tout les films et les affiche dans la vue.
     * Il gére aussi les filtres : titre, auteur, note, et la durée.
     */
    public function All(): void
    {
        $songs = Song::getAllSong();

        if(isset($_POST['search']) && !empty($_POST['search'])){
            $filter = strtolower(htmlspecialchars($_POST['search']));
            $filteredSongs = [];
            $filterdArray = array_filter($songs, function($song) use($filter){
                return stripos($song->getTitle(), $filter) !== false;
                }
            );

            if(count($filterdArray) === 0){
                array_map(function($song) use(&$filter, &$filteredSongs){
                    $title = $song->getTitle();
                    $titleWords = explode(' ', $title);
                    $searchWords = explode(" ",$filter);
                    array_map(function($word) use($searchWords, $song, &$filteredSongs){
                        array_map(function($searchWord) use($word,$song, &$filteredSongs){
                            if(levenshtein($word, strtolower($searchWord)) <= 2){
                                if(!in_array($song,$filteredSongs)){
                                    array_push($filteredSongs,$song);
                                }
                            }
                        },$searchWords);
                    },$titleWords);
                },$songs);
                $albums = $filteredSongs;

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
                    case 'duration':
                        return $a->getDuration() <=> $b->getDuration();
                    case 'note':
                        return $a->getNote() <=> $b->getNote();
                    default:
                        return 0;
                }
            });
        }
        require_once('Views/songs/index.php');
    }

    /**
     * Ajoute une nouvell musique dans la base de donnée.
     * 
     * Cette fonction ajoute une nouvelle musiques et renvoie vers la liste des musiques.
     * Si un ou plusieurs champ(s) est/sont invalide(s), ajoute l'erreur pour l'afficher dans la vue.
     * Si la note n'est pas comprise entre 0 et 10 (inclus), ajoute l'erreur pour l'afficher dans la vue.
     * Si l'utilisateur n'est pas connecté, renvoie vers la page de connexion.
     * 
     */
    public function new(): void
    {
        $errors = [];
        $albums = Album::getAllAlbums();
        if(isset($_SESSION['currentUser']) && !empty($_SESSION['currentUser'])){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if(isset($_POST['title']) && !empty($_POST['title']) && isset($_POST['duration']) && !empty($_POST['duration']) && isset($_POST['note']) && !empty($_POST['note']) && isset($_POST['albumId']) && !empty($_POST['albumId'])){
                    if($_POST['note']>=0 && $_POST['note']<=10){
                        $title = htmlspecialchars($_POST['title']);
                        $duration = htmlspecialchars($_POST['duration']);
                        $note = htmlspecialchars($_POST['note']);
                        $albumId = htmlspecialchars($_POST['albumId']);

                        $newSong = new Song($title, $note, $duration, $albumId, null);
                        $newSong->addNewSong();

                        header("location:".BASE_URL."/song/All");
                    }else{
                        array_push($errors,"La note doit être comprise entre 0 et 10 (inclus).");
                    }
                }else{
                    array_push($errors,"Un ou plusieurs champ(s) vide(s) !");
                }
            }
        }else{
            header("location:".BASE_URL."/connexion");                
        }
        require_once('Views/songs/new_song.php');
    }

    /**
     * Met à jour les informations de la musique dans la base de donnée.
     * 
     * Cette fonction met à jour les informations de la musique et renvoie vers la liste des musiques.
     * Si un ou plusieurs champ(s) est/sont invalide(s), ajoute l'erreur pour l'afficher dans la vue.
     * Si la note n'est pas comprise entre 0 et 10 (inclus), ajoute l'erreur pour l'afficher dans la vue.
     * Si l'utilisateur n'est pas connecté, renvoie vers la page de connexion.
     * 
     * @param int $id L'identifiant de la musique.
     */
    public function update(int $id){
        $errors = [];
        $albums = Album::getAllAlbums();
        $updatedSong = Song::getSongById($id);
        if($updatedSong instanceof Song){
            if(isset($_SESSION['currentUser']) && !empty($_SESSION['currentUser'])){
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if(isset($_POST['title']) && !empty($_POST['title']) && isset($_POST['duration']) && !empty($_POST['duration']) && isset($_POST['note']) && !empty($_POST['note']) && isset($_POST['albumId']) && !empty($_POST['albumId'])){
                        if($_POST['note']>=0 && $_POST['note']<=10){
                            $title = htmlspecialchars($_POST['title']);
                            $duration = htmlspecialchars($_POST['duration']);
                            $note = htmlspecialchars($_POST['note']);
                            $albumId = htmlspecialchars($_POST['albumId']);

                            $updatedSong->setTitle($title);
                            $updatedSong->setDuration($duration);
                            $updatedSong->setNote($note);
                            $updatedSong->setAlbumId($albumId);

                            $updatedSong->updateSong();

                            header("location:".BASE_URL."/song/All");
                        }else{
                            array_push($errors,"La note doit être comprise entre 0 et 10 (inclus).");
                        }
                    }else{
                        array_push($errors,"Un ou plusieurs champ(s) vide(s) !");
                    }
                }
                require_once('Views/songs/update_song.php');
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
     * Affiche les informations d'une musique.
     * 
     * Cette fonction affiche la vue du détail d'une musique et les boutons d'actions associés. 
     * 
     * @param int $id L'identifiant de la musique.
     */
    public function show(int $id){
        $song = Song::getSongById($id);
        if($song instanceof Song){
            $album = Album::getAlbumById($song->getAlbumId());
            require_once('Views/songs/details_song.php');
        }else{
            require_once('Controllers/ErrorController.php');
            $controller = new ErrorController();
            $controller->noFoundError();
        }
    }
}