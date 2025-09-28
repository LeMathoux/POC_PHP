<?php

/**
 * Controller HomeController gérant l'affichage de la vue home.
 * 
 * Ce controller contient la gestion de l'affichage de la page d'accueil :
 * 
 * home() => affiche la vue de la page d'accueil.
 */
class HomeController{

    /**
     * Affiche la vue de la page d'accueil.
     * 
     * Cette fonction affiche la vue de la page d'accueil.
     */
    public function home() : void {
        require_once('Views/home.php');
    }
}