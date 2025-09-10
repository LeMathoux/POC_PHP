<?php

class MediaController{

    public function index(): void
    {
        require_once('Views/mediaIndex.php');
    }

    public function all(): void
    {
        require_once('Views/medias/index.php');
    }
}