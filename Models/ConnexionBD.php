<?php

/**
 * Cette fonction permet la connexion entre la plateforme et la base de donnée
 * 
 * @return PDO $connexion
 */
function Connexion(){
    $password = "";
    $login = "root";
    $database = "mediatheque";
    $host = "localhost";

    try{
        $connexion = new PDO("mysql:host=$host;dbname=$database",$login,$password);
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connexion;
    }catch(PDOException $e){
        echo "Connexion With Database Error : ".$e;
    }
}