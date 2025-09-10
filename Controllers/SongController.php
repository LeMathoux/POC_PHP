<?php

class SongController{
    
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

    public function new(): void
    {
        $errors = [];
        $albums = Album::getAllAlbums();
        if(isset($_SESSION['currentUser']) && !empty($_SESSION['currentUser'])){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if(isset($_POST['title']) && !empty($_POST['title']) && isset($_POST['duration']) && !empty($_POST['duration']) && isset($_POST['note']) && !empty($_POST['note']) && isset($_POST['albumId']) && !empty($_POST['albumId'])){
                    $title = htmlspecialchars($_POST['title']);
                    $duration = htmlspecialchars($_POST['duration']);
                    $note = htmlspecialchars($_POST['note']);
                    $albumId = htmlspecialchars($_POST['albumId']);

                    $newSong = new Song($title, $note, $duration, $albumId, null);
                    $newSong->addNewSong();

                    header("location:".BASE_URL."/song/All");

                }else{
                    array_push($errors,"Un ou plusieurs champ(s) vide(s) !");
                }
            }
        }else{
            header("location:".BASE_URL."/connexion");                
        }
        require_once('Views/songs/new_song.php');
    }

    public function update(int $id){
        $errors = [];
        $albums = Album::getAllAlbums();
        $updatedSong = Song::getSongById($id);
        if($updatedSong instanceof Song){
            if(isset($_SESSION['currentUser']) && !empty($_SESSION['currentUser'])){
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if(isset($_POST['title']) && !empty($_POST['title']) && isset($_POST['duration']) && !empty($_POST['duration']) && isset($_POST['note']) && !empty($_POST['note']) && isset($_POST['albumId']) && !empty($_POST['albumId'])){
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