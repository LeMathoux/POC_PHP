<?php

/**
 * Controller ErrorController gérant les erreurs.
 * 
 * Ce controller permet de gérer l'affichage des pages erreurs :
 * 
 * notFoundError() => affiche la page 404 not found.
 */
class ErrorController{

    /**
     * Affiche la vue de l'erreur 404 not found.
     * 
     * Cette fonction affiche la vue de l'erreur 404 not found.
     */
    public function noFoundError(){
        require_once('Views/errors/404.php');
    }
}