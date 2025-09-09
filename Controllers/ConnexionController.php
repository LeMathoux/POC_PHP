<?php

require_once __DIR__.'/../Models/User.php';

class ConnexionController{

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

        require_once('Views/login.php');
    }

    public function registration(): void
    {
        $errors = [];
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
                            if (stripos($password, $userName) !== false) {
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

        require_once('Views/registration.php');
    }

    public function logout() :void
    {
        session_destroy();
        header('location:../');
    }
}