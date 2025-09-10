<?php

class MediaController{

    public function index(): void
    {
        require_once('Views/mediaIndex.php');
    }

    public function all(): void
    {
        $albums = Album::getAllAlbums();
        $books = Book::getAllBooks();
        $movies = Movie::getAllMovies();

        $medias = array_merge($books, $albums, $movies);

        if(isset($_POST['search']) && !empty($_POST['search'])){
            $filter = strtolower(htmlspecialchars($_POST['search']));
            $filteredMedias = [];
            $filterdArray = array_filter($medias, function($media) use($filter){
                return stripos($media->getTitle(), $filter) !== false ||
                    stripos($media->getAuthor(), $filter) !== false;
                }
            );

            if(count($filterdArray) === 0){
                array_map(function($media) use(&$filter, &$filteredAlbums){
                    $words = [];
                    $title = $media->getTitle();
                    $author = $media->getAuthor();
                    $titleWords = explode(' ', $title);
                    $authorWords = explode(' ', $author);
                    $words = array_merge($titleWords, $authorWords);
                    $searchWords = explode(" ",$filter);
                    array_map(function($word) use($searchWords, $media, &$filteredAlbums){
                        array_map(function($searchWord) use($word,$media, &$filteredAlbums){
                            if(levenshtein($word, strtolower($searchWord)) <= 2){
                                if(!in_array($media,$filteredAlbums)){
                                    array_push($filteredAlbums,$media);
                                }
                            }
                        },$searchWords);
                    },$words);
                },$medias);
                $medias = $filteredMedias;

            }else{
                $medias = $filterdArray;
            }
        }

        if(isset($_POST['sort']) && !empty($_POST['sort'])){
            $sortBy = $_POST['sort'] ?? 'title';
            usort($medias, function($a, $b) use ($sortBy) {
                switch ($sortBy) {
                    case 'title':
                        return strcmp(strtolower($a->getTitle()), strtolower($b->getTitle()));
                    case 'author':
                        return strcmp(strtolower($a->getAuthor()), strtolower($b->getAuthor()));
                    case 'available':
                        return $a->getAvailable() <=> $b->getAvailable();
                    default:
                        return 0;
                }
            });
        }
        require_once('Views/medias/index.php');
    }
}