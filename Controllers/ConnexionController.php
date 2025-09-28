<?php

require_once __DIR__.'/../Models/User.php';

/**
 * Controller ConnexionController gérant la sécurité de l'application.
 * 
 * Ce controller gére la sécurité de la plateforme en terme d'authentification et de création de compte :
 * 
 * index() => affiche la vue de connexion avec le formulaire associé ainsi que le traitement des erreurs de saisie.
 * registration() => affiche la vue d'inscription avec le formulaire associé ainsi que le traitement des erreurs de saisie.
 * logout() => déconnecte l'utilisateur et le redirige vers l'accueil.
 */
class ConnexionController{

    /**
     * Affiche le formulaire de connexion.
     * 
     * Cette fonction permet à l'utilisateur de se connecter à la plateforme.
     * Si un utilisateur est déjà connecté, renvoie vers l'accueil.
     * Si un ou plusieurs champ(s) est/sont invalides, une erreur sera affichée sur la vue.
     * Si l'email est incorrect, une erreur sera affichée sur la vue.
     */
    public function index(): void
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['password']) && !empty($_POST['password'])){
                if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    $user = new User($_POST['email'],$_POST['password'], null, null, null, null);
                    $_SESSION['currentUser'] = $user->verificationAuth();
                    if($_SESSION['currentUser'] instanceof User){
                        header("location:./");
                    }else{
                        array_push($errors,"Email ou Mot de passe incorrect !");
                    }  
                }else{
                    array_push($errors,"Email Incorrect !");
                }
            }else{
                array_push($errors,"Un ou plusieurs champ(s) vide(s) !");
            }
        }

        require_once('Views/security/login.php');
    }

    /**
     * Gére l'inscription d'un nouvel utilisateur.
     * 
     * Cette fonction Affiche la vue registration avec le formulaire d'inscription.
     * Si un ou plusieurs champ(s) est/sont invalides, une erreur sera affichée sur la vue.
     * Si l'email est incorrect, une erreur sera affichée sur la vue.
     * Si le mot de passe contient le nom de l'utilisateur, une erreur sera affichée sur la vue.
     * Si le mot de passe n'est pas valide, une erreur sera affichée sur la vue.
     * Si la confirmation du mot de passe n'est pas identique, une erreur sera affichée sur la vue.
     */
    public function registration(): void
    {
        $errors = [];
        $passContainUserName = false;
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(isset($_POST['userName']) && !empty($_POST['userName']) && isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['password']) && !empty($_POST['password']) && isset($_POST['passwordConfirm']) && !empty($_POST['passwordConfirm'])){
              
                $email = htmlspecialchars($_POST['email']);
                $userName = htmlspecialchars($_POST['userName']);
                $password = htmlspecialchars($_POST['password']);
                $passwordConfirm = htmlspecialchars($_POST['passwordConfirm']);

                if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    if($password === $passwordConfirm){
                        if(preg_match($pattern, $password)){
                            $userNameWords = explode(" ",$userName);
                            foreach($userNameWords as $userNameWord){
                                if(stripos($password, $userNameWord) !== false){
                                    $passContainUserName = true;
                                }
                            }

                            if ($passContainUserName) {
                                array_push($errors,"Le mot de passe ne doit pas contenir votre nom de compte !");   
                            }else{
                                $newUser = new User($email, $password, $userName, null, null, null, null);
                                $result = $newUser->addNewUser();
                                if($result instanceof User){
                                    $_SESSION['currentUser'] = $result;
                                    header('location:../');
                                }
                            }
                        }else{
                            array_push($errors,"Le mot de passe doit contenir 8 caractères min, majuscule, minuscule, chiffre et caractères spéciaux !"); 
                        }
                    }else{
                        array_push($errors,"Le mot de passe de confirmation doit etre identique !");                        
                    }
                }else{
                    array_push($errors,"Email Incorrect !");
                }
            }else{
                array_push($errors,"Un ou plusieurs champ(s) vide(s) !");
            }
        }

        require_once('Views/security/registration.php');
    }

    /**
     * Déconnecte l'utilisateur actif.
     * 
     * Cette fonction déconnecte l'utilisateur et renvoie vers la page d'accueil.
     */
    public function logout() :void
    {
        session_destroy();
        header('location:../');
    }
}